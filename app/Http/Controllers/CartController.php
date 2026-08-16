<?php

namespace App\Http\Controllers;

use App\Support\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('cart.index', [
            'items' => Cart::items(),
            'total' => Cart::total(),
            'coupon' => Cart::coupon(),
            'discount' => Cart::discount(),
            'grandTotal' => Cart::grandTotal(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $variant = \App\Models\ProductVariant::findOrFail($data['variant_id']);
        $existingQty = session('cart.'.$variant->id, 0);
        $newTotalQty = $existingQty + $data['quantity'];

        if ($variant->stock < $newTotalQty) {
            return back()->withErrors(['quantity' => "Stok produk tidak mencukupi (Tersisa {$variant->stock} pcs)."]);
        }

        Cart::add($data['variant_id'], $data['quantity']);

        return back()->with('status', 'Ditambahkan ke keranjang.');
    }

    public function update(Request $request, int $variantId): RedirectResponse
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:0']]);

        if ($data['quantity'] > 0) {
            $variant = \App\Models\ProductVariant::findOrFail($variantId);
            if ($variant->stock < $data['quantity']) {
                return back()->withErrors(['quantity' => "Stok produk tidak mencukupi (Tersisa {$variant->stock} pcs)."]);
            }
        }

        Cart::update($variantId, $data['quantity']);

        return back();
    }

    public function destroy(int $variant): RedirectResponse
    {
        Cart::remove($variant);

        return back();
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);
        $result = Cart::applyCoupon($request->string('code')->toString());

        if (! $result['success']) {
            return back()->withErrors(['coupon' => $result['message']]);
        }

        return back()->with('coupon_status', $result['message']);
    }

    public function removeCoupon(): RedirectResponse
    {
        Cart::removeCoupon();

        return back()->with('coupon_status', 'Kupon dilepas.');
    }
}
