<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MidtransWebhookController extends Controller
{
    // ponytail: no signature verification / queue dispatch yet — wire real Midtrans Snap +
    // signature_key verification + Bus::dispatch(ProcessMidtransNotification) when API key is ready.
    // Idempotency check below is what stays true either way.
    public function handle(Request $request, Order $order): RedirectResponse
    {
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order);
        }

        $order->update([
            'payment_status' => 'paid',
            'shipping_status' => 'diproses',
            'midtrans_transaction_id' => $request->string('midtrans_transaction_id')->toString() ?: (string) Str::uuid(),
        ]);

        return redirect()->route('orders.show', $order)->with('status', 'Pembayaran berhasil, pesanan sedang diproses.');
    }
}
