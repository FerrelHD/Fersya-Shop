<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Invoice {{ $order->order_number }} - Fersya Shop</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet"/>
<style>
  body { font-family: 'Plus Jakarta Sans', sans-serif; color: #1c1d1b; background: #f8faf6; margin: 0; padding: 40px 20px; }
  .invoice-card { max-width: 800px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
  .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #e2e8f0; padding-bottom: 24px; margin-bottom: 32px; }
  .brand-title { font-size: 28px; font-weight: 700; color: #2d4a3e; margin: 0; }
  .brand-sub { font-size: 13px; color: #64748b; margin-top: 4px; }
  .inv-number { font-size: 22px; font-weight: 700; color: #2d4a3e; text-align: right; }
  .inv-date { font-size: 13px; color: #64748b; margin-top: 4px; text-align: right; }
  .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px; }
  .detail-box h4 { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; margin: 0 0 8px 0; }
  .detail-box p { font-size: 14px; line-height: 1.6; margin: 0; font-weight: 600; }
  table { width: 100%; border-collapse: collapse; margin-bottom: 32px; }
  th { background: #f1f5f0; color: #2d4a3e; text-align: left; padding: 12px 16px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
  td { padding: 16px; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
  .text-right { text-align: right; }
  .totals-row { display: flex; justify-content: flex-end; margin-top: 16px; }
  .totals-table { width: 300px; }
  .totals-table div { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; color: #64748b; }
  .totals-table .grand-total { font-size: 18px; font-weight: 700; color: #2d4a3e; border-top: 2px solid #2d4a3e; padding-top: 12px; margin-top: 8px; }
  .badge { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
  .badge-paid { background: #dcfce7; color: #15803d; }
  .badge-pending { background: #fef3c7; color: #b45309; }
  .actions { text-align: center; margin-top: 32px; }
  .btn-print { background: #2d4a3e; color: #ffffff; border: none; padding: 14px 28px; font-size: 14px; font-weight: 700; border-radius: 10px; cursor: pointer; }
  @media (max-width: 640px) {
    body { padding: 16px 8px; }
    .invoice-card { padding: 20px; }
    .header { flex-direction: column; gap: 16px; text-align: left; }
    .inv-number, .inv-date { text-align: left; }
    .details-grid { grid-template-columns: 1fr; }
    .detail-box.text-right { text-align: left; }
    .totals-table { width: 100%; }
  }
  @media print {
    body { background: #ffffff; padding: 0; }
    .invoice-card { box-shadow: none; padding: 0; }
    .actions { display: none; }
  }
</style>
</head>
<body>

<div class="invoice-card">
  <div class="header">
    <div>
      <h1 class="brand-title">Fersya Shop</h1>
      <div class="brand-sub">Artisan Bakery & Organic Goods</div>
    </div>
    <div>
      <div class="inv-number">INVOICE</div>
      <div class="inv-date">{{ $order->order_number }}<br>{{ $order->created_at->format('d M Y, H:i') }}</div>
    </div>
  </div>

  <div class="details-grid">
    <div class="detail-box">
      <h4>Penerima Pesanan</h4>
      <p>
        {{ $order->guest_name }} ({{ $order->guest_phone }})<br>
        {{ $order->shippingAddress?->address }}<br>
        {{ $order->shippingAddress?->city }}, {{ $order->shippingAddress?->province }} {{ $order->shippingAddress?->postal_code }}
      </p>
    </div>
    <div class="detail-box text-right">
      <h4>Status Pembayaran</h4>
      <p>
        <span class="badge {{ $order->payment_status === 'paid' ? 'badge-paid' : 'badge-pending' }}">
          {{ $order->payment_status === 'paid' ? 'LUNAS (PAID)' : 'MENUNGGU PEMBAYARAN' }}
        </span>
      </p>
      @if ($order->shipping_receipt_number)
      <h4 style="margin-top: 16px;">No. Resi Pengiriman</h4>
      <p style="font-family: monospace; font-size: 16px;">{{ $order->shipping_receipt_number }}</p>
      @endif
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Produk / Varian</th>
        <th class="text-right">Harga</th>
        <th class="text-right">Jumlah</th>
        <th class="text-right">Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($order->items as $item)
      <tr>
        <td>
          <strong>{{ $item->variant->product->name }}</strong><br>
          <span style="font-size: 12px; color: #64748b;">Varian: {{ $item->variant->name }}</span>
        </td>
        <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
        <td class="text-right">x{{ $item->quantity }}</td>
        <td class="text-right"><strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong></td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="totals-row">
    <div class="totals-table">
      <div><span>Subtotal</span><span>Rp {{ number_format($order->items->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</span></div>
      <div><span>Ongkos Kirim</span><span style="color: #15803d; font-weight: 700;">GRATIS</span></div>
      @if ($order->discount_amount > 0)
      <div><span>Diskon Kupon ({{ $order->coupon_code }})</span><span style="color: #dc2626;">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span></div>
      @endif
      <div class="grand-total"><span>Total Bayar</span><span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></div>
    </div>
  </div>

  <div class="actions">
    <button onclick="window.print()" class="btn-print">🖨️ Cetak / Simpan PDF Invoice</button>
  </div>
</div>

</body>
</html>
