<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->order->load(['items.variant.product', 'shippingAddress']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pesanan ' . $this->order->order_number . ' - Fersya Shop',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_created',
        );
    }
}
