<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'order_number' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string'],
        ]);

        $order = Order::where('order_number', $data['order_number'])
            ->where('payment_status', 'paid')
            ->whereHas('items.variant', fn ($q) => $q->where('product_id', $product->id))
            ->first();

        if (! $order) {
            return back()->with('review_status', 'Nomor pesanan tidak ditemukan atau belum selesai.');
        }

        $product->reviews()->create([
            'order_id' => $order->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return back()->with('review_status', 'Terima kasih atas ulasannya!');
    }
}
