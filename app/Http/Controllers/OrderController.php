<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function show(Order $order): View|RedirectResponse
    {
        if (! in_array($order->order_number, session('accessible_orders', []))) {
            return redirect()->route('orders.search')
                ->with('info', 'Masukkan nomor pesanan atau nomor WhatsApp untuk melihat detail pesanan.');
        }

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
                // Beri akses session agar bisa lihat detail
                session()->push('accessible_orders', $exactOrder->order_number);
                return redirect()->route('orders.show', $exactOrder);
            }

            $cleanPhone = preg_replace('/[^0-9]/', '', $query);
            if (strlen($cleanPhone) >= 10) {
                $orders = Order::where('guest_phone', $cleanPhone)
                    ->orWhere('guest_phone', $query)
                    ->with(['items.variant.product'])
                    ->latest()
                    ->get();

                // Beri akses session untuk semua pesanan yang ditemukan via nomor HP
                foreach ($orders as $found) {
                    session()->push('accessible_orders', $found->order_number);
                }
            }
        }

        return view('orders.search', [
            'query' => $query,
            'orders' => $orders,
        ]);
    }

    public function invoice(Order $order): View|RedirectResponse
    {
        if (! in_array($order->order_number, session('accessible_orders', []))) {
            return redirect()->route('orders.search')
                ->with('info', 'Masukkan nomor pesanan atau nomor WhatsApp untuk melihat invoice.');
        }

        $order->load(['items.variant.product', 'shippingAddress']);

        return view('orders.invoice', ['order' => $order]);
    }
}
