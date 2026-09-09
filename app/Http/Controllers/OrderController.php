<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function show(Order $order): View
    {
        $order->load(['items.variant.product', 'shippingAddress']);

        return view('orders.show', ['order' => $order]);
    }

    public function search(Request $request): View|RedirectResponse
    {
        $query = trim($request->input('q', ''));
        $orders = collect();

        if ($query !== '') {
            $exactOrder = Order::where('order_number', strtoupper($query))->first();
            if ($exactOrder) {
                return redirect()->route('orders.show', $exactOrder);
            }

            $cleanPhone = preg_replace('/[^0-9]/', '', $query);
            // Hanya izinkan pencarian no telepon jika format nomor valid (minimal 10 digit) dan cocok persis
            if (strlen($cleanPhone) >= 10) {
                $orders = Order::where('guest_phone', $cleanPhone)
                    ->orWhere('guest_phone', $query)
                    ->with(['items.variant.product'])
                    ->latest()
                    ->get();
            }
        }

        return view('orders.search', [
            'query' => $query,
            'orders' => $orders,
        ]);
    }

    public function invoice(Order $order): View
    {
        $order->load(['items.variant.product', 'shippingAddress']);

        return view('orders.invoice', ['order' => $order]);
    }
}
