<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan {{ $order->order_number }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f7f6f2; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #2d3748;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f7f6f2; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); max-width: 90%; text-align: left;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #2D4A3E; padding: 32px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 600; letter-spacing: -0.5px;">🌿 Fersya Shop</h1>
                            <p style="margin: 8px 0 0 0; color: #e2e8f0; font-size: 14px;">Terima kasih atas pesanan Anda!</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 32px;">
                            <h2 style="margin: 0 0 16px 0; color: #2D4A3E; font-size: 20px;">Halo {{ $order->guest_name }},</h2>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #4a5568;">
                                Pesanan Anda dengan nomor <strong style="color: #2D4A3E;">{{ $order->order_number }}</strong> telah berhasil kami terima. Berikut adalah rincian pesanan Anda:
                            </p>

                            <!-- Items Table -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; margin-bottom: 24px;">
                                <thead>
                                    <tr style="border-bottom: 2px solid #edf2f7;">
                                        <th align="left" style="padding: 12px 0; color: #718096; font-size: 12px; text-transform: uppercase;">Produk</th>
                                        <th align="center" style="padding: 12px 0; color: #718096; font-size: 12px; text-transform: uppercase;">Jumlah</th>
                                        <th align="right" style="padding: 12px 0; color: #718096; font-size: 12px; text-transform: uppercase;">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                    <tr style="border-bottom: 1px solid #edf2f7;">
                                        <td style="padding: 12px 0; font-size: 14px; font-weight: 500;">
                                            {{ $item->variant->product->name }}
                                            <span style="display: block; font-size: 12px; color: #718096;">Varian: {{ $item->variant->name }}</span>
                                        </td>
                                        <td align="center" style="padding: 12px 0; font-size: 14px; color: #4a5568;">x{{ $item->quantity }}</td>
                                        <td align="right" style="padding: 12px 0; font-size: 14px; font-weight: 600; color: #2D4A3E;">
                                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Summary -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 24px; background-color: #f8fafc; padding: 16px; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 4px 0; font-size: 14px; color: #4a5568;">Ongkos Kirim</td>
                                    <td align="right" style="padding: 4px 0; font-size: 14px; color: #4a5568;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr style="border-top: 1px solid #e2e8f0;">
                                    <td style="padding: 12px 0 4px 0; font-size: 16px; font-weight: 700; color: #2D4A3E;">Total Pembayaran</td>
                                    <td align="right" style="padding: 12px 0 4px 0; font-size: 16px; font-weight: 700; color: #2D4A3E;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </table>

                            <!-- Shipping Address -->
                            @if ($order->shippingAddress)
                            <div style="margin-bottom: 32px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                <h4 style="margin: 0 0 8px 0; color: #2D4A3E; font-size: 14px;">Alamat Pengiriman</h4>
                                <p style="margin: 0; font-size: 13px; color: #4a5568; line-height: 1.5;">
                                    <strong>{{ $order->shippingAddress->recipient_name }}</strong> ({{ $order->shippingAddress->phone }})<br>
                                    {{ $order->shippingAddress->address }}<br>
                                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }} {{ $order->shippingAddress->postal_code }}
                                </p>
                            </div>
                            @endif

                            <!-- CTA Button -->
                            <div style="text-align: center;">
                                <a href="{{ route('orders.show', $order) }}" style="display: inline-block; background-color: #2D4A3E; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-size: 14px; font-weight: 600;">Lihat Detail Pesanan</a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f7f6f2; padding: 24px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; font-size: 12px; color: #a0aec0;">&copy; {{ date('Y') }} Fersya Shop. Produk Organik & Alami untuk Ritual Pagi.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
