<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan {{ $order->order_number }} Telah Dikirim</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f7f6f2; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #2d3748;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f7f6f2; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); max-width: 90%; text-align: left;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #2D4A3E; padding: 32px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 600;">🌿 Fersya Shop</h1>
                            <p style="margin: 8px 0 0 0; color: #e2e8f0; font-size: 14px;">Kabar gembira! Paket Anda sedang dalam perjalanan 🚚</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 32px;">
                            <h2 style="margin: 0 0 16px 0; color: #2D4A3E; font-size: 20px;">Halo {{ $order->guest_name }},</h2>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #4a5568;">
                                Pesanan Anda nomor <strong style="color: #2D4A3E;">{{ $order->order_number }}</strong> telah diserahterimahkan ke pihak kurir ekpedisi dan dalam pengiriman.
                            </p>

                            <!-- Tracking Box -->
                            <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 20px; border-radius: 12px; margin-bottom: 28px; text-align: center;">
                                <span style="display: block; font-size: 12px; text-transform: uppercase; color: #166534; font-weight: 600; margin-bottom: 4px;">Nomor Resi Pengiriman</span>
                                <span style="display: block; font-size: 22px; font-weight: 700; color: #2D4A3E; letter-spacing: 1px;">{{ $order->shipping_receipt_number ?: '-' }}</span>
                            </div>

                            <p style="margin: 0 0 24px 0; font-size: 14px; color: #718096; line-height: 1.5;">
                                Anda dapat melacak posisi paket secara berkala menggunakan nomor resi di atas melalui website resmi ekspedisi terkait.
                            </p>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin-top: 32px;">
                                <a href="{{ route('orders.show', $order) }}" style="display: inline-block; background-color: #2D4A3E; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-size: 14px; font-weight: 600;">Cek Status Pesanan</a>
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
