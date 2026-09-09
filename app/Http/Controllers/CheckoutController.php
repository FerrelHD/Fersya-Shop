<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductVariant;
use App\Support\Cart;
use App\Support\ShippingCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $items = Cart::items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        return view('checkout.index', [
            'items' => $items,
            'total' => Cart::total(),
            'coupon' => Cart::coupon(),
            'discount' => Cart::discount(),
            'grandTotal' => Cart::grandTotal(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $items = Cart::items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $data = $request->validate([
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_phone' => ['required', 'string', 'max:30'],
            'guest_email' => ['nullable', 'email'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
        ]);

        $shippingCost = ShippingCalculator::estimate($data['city']);
        $subtotal = Cart::total();
        $discount = Cart::discount();
        $coupon = Cart::coupon();

        try {
            $order = DB::transaction(function () use ($data, $items, $shippingCost, $subtotal, $discount, $coupon) {
                // Verifikasi stok dengan lock untuk mencegah race condition
                foreach ($items as $item) {
                    $variant = ProductVariant::where('id', $item['variant']->id)
                        ->lockForUpdate()
                        ->first();

                    if (! $variant || $variant->stock < $item['quantity']) {
                        $productName = $variant?->product?->name ?? 'Produk';
                        $variantName = $variant?->name ?? '';
                        $remaining = $variant?->stock ?? 0;
                        throw new \RuntimeException("Stok untuk {$productName} ({$variantName}) tidak mencukupi. Tersisa {$remaining} pcs.");
                    }
                }

                $order = Order::create([
                    'guest_name' => $data['guest_name'],
                    'guest_phone' => $data['guest_phone'],
                    'guest_email' => $data['guest_email'] ?? null,
                    'order_number' => 'FS-'.strtoupper(Str::random(8)),
                    'total_amount' => max(0, $subtotal + $shippingCost - $discount),
                    'shipping_cost' => $shippingCost,
                    'discount_amount' => $discount,
                    'coupon_code' => $coupon?->code,
                    'payment_status' => 'pending',
                    'shipping_status' => 'menunggu_pembayaran',
                ]);

                foreach ($items as $item) {
                    $order->items()->create([
                        'product_variant_id' => $item['variant']->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['variant']->price(),
                    ]);

                    ProductVariant::where('id', $item['variant']->id)
                        ->decrement('stock', $item['quantity']);
                }

                $order->shippingAddress()->create([
                    'recipient_name' => $data['guest_name'],
                    'phone' => $data['guest_phone'],
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'province' => $data['province'],
                    'postal_code' => $data['postal_code'],
                ]);

                return $order;
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['stock' => $e->getMessage()])->withInput();
        }

        Cart::clear();

        if ($order->guest_email) {
            try {
                \Illuminate\Support\Facades\Mail::to($order->guest_email)->send(new \App\Mail\OrderCreatedMail($order));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Gagal mengirim email pesanan: ' . $e->getMessage());
            }
        }

        return redirect()->route('orders.show', $order);
    }
}
