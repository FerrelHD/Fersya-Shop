<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function show(Order $order): View
    {
        $order->load(['items.variant.product', 'shippingAddress']);

        return view('orders.show', ['order' => $order]);
    }
}
