<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\Cart;
use App\Support\ShippingCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return view('checkout.index', ['items' => $items, 'total' => Cart::total()]);
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

        // Verify stock for all items
        foreach ($items as $item) {
            if ($item['variant']->stock < $item['quantity']) {
                return back()->withErrors([
                    'stock' => "Stok untuk {$item['variant']->product->name} ({$item['variant']->name}) tidak mencukupi. Tersisa {$item['variant']->stock} pcs."
                ])->withInput();
            }
        }

        $shippingCost = ShippingCalculator::estimate($data['city']);
        $subtotal = Cart::total();

        $order = Order::create([
            'guest_name' => $data['guest_name'],
            'guest_phone' => $data['guest_phone'],
            'guest_email' => $data['guest_email'] ?? null,
            'order_number' => 'FS-'.strtoupper(Str::random(8)),
            'total_amount' => $subtotal + $shippingCost,
            'shipping_cost' => $shippingCost,
            'payment_status' => 'pending',
            'shipping_status' => 'menunggu_pembayaran',
        ]);

        foreach ($items as $item) {
            $order->items()->create([
                'product_variant_id' => $item['variant']->id,
                'quantity' => $item['quantity'],
                'price' => $item['variant']->price(),
            ]);

            // Decrement variant stock automatically
            $item['variant']->decrement('stock', $item['quantity']);
        }

        $order->shippingAddress()->create([
            'recipient_name' => $data['guest_name'],
            'phone' => $data['guest_phone'],
            'address' => $data['address'],
            'city' => $data['city'],
            'province' => $data['province'],
            'postal_code' => $data['postal_code'],
        ]);

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
