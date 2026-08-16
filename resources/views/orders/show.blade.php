<x-layouts.app title="Pesanan {{ $order->order_number }} | Fersya Shop">
<section class="px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto py-16">
@if (session('status'))
<div class="bg-primary-fixed text-on-primary-fixed rounded-xl p-6 mb-8 font-body-md shadow-sm">{{ session('status') }}</div>
@endif

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 border-b border-outline-variant pb-6">
<div>
<span class="text-xs text-on-surface-variant font-bold uppercase tracking-widest block mb-1">Rincian Pesanan</span>
<h1 class="font-headline-lg text-3xl sm:text-4xl text-primary font-bold">{{ $order->order_number }}</h1>
<p class="text-on-surface-variant text-sm mt-1">Dibuat pada: {{ $order->created_at->format('d M Y, H:i') }}</p>
</div>
<div class="flex flex-wrap gap-2">
<span class="px-4 py-2 rounded-full text-xs font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-primary-fixed text-on-primary-fixed' : 'bg-amber-100 text-amber-800' }}">
Pembayaran: {{ $order->payment_status === 'paid' ? 'Sudah Bayar (Lunas)' : 'Menunggu Pembayaran' }}
</span>
<span class="px-4 py-2 rounded-full text-xs font-bold uppercase bg-surface-container text-on-surface-variant">
Pengiriman: {{ ucfirst($order->shipping_status) }}
</span>
</div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
<!-- Detail Produk & Alamat -->
<div class="lg:col-span-7 space-y-8">
<div>
<h2 class="font-headline-md text-xl text-primary font-bold mb-4">Item Produk</h2>
<div class="space-y-4">
@foreach ($order->items as $item)
<div class="flex justify-between items-center bg-surface p-6 rounded-2xl ambient-shadow">
<div>
<h4 class="font-headline-md text-base text-primary font-bold">{{ $item->variant->product->name }}</h4>
<p class="text-xs text-on-surface-variant">Varian: {{ $item->variant->name }} (x{{ $item->quantity }})</p>
@if ($order->payment_status === 'paid')
<a href="{{ route('products.show', ['product' => $item->variant->product, 'order' => $order->order_number]) }}#review-section" class="inline-block text-xs text-primary font-bold hover:underline mt-2">
⭐ Beri Ulasan Produk Ini
</a>
@endif
</div>
<span class="font-bold text-primary">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
</div>
@endforeach
</div>
</div>

@if ($order->shippingAddress)
<div class="bg-surface p-6 rounded-2xl ambient-shadow">
<h3 class="font-headline-md text-lg text-primary font-bold mb-3">Alamat Pengiriman</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">
<strong class="text-primary">{{ $order->shippingAddress->recipient_name }}</strong> ({{ $order->shippingAddress->phone }})<br>
{{ $order->shippingAddress->address }}<br>
{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }} {{ $order->shippingAddress->postal_code }}
</p>
</div>
@endif

@if ($order->shipping_receipt_number)
<div class="bg-primary-fixed/30 border border-primary-fixed p-6 rounded-2xl">
<span class="text-xs text-primary font-bold uppercase tracking-wider block mb-1">Nomor Resi Pengiriman</span>
<span class="text-2xl font-bold text-primary block tracking-wider">{{ $order->shipping_receipt_number }}</span>
<p class="text-xs text-on-surface-variant mt-2">Gunakan nomor resi di atas untuk melacak posisi paket Anda via kurir ekspedisi.</p>
</div>
@endif
</div>

<!-- Pembayaran QRIS / WA & Ringkasan -->
<div class="lg:col-span-5 space-y-6">
<div class="bg-surface p-8 rounded-2xl ambient-shadow">
<h2 class="font-headline-md text-xl text-primary font-bold mb-4">Ringkasan Total</h2>
<div class="space-y-2 text-sm">
<div class="flex justify-between text-on-surface-variant"><span>Ongkos Kirim</span><span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
<div class="flex justify-between font-bold text-primary text-xl border-t border-outline-variant pt-4 mt-2">
<span>Total Bayar</span>
<span class="text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
</div>
</div>

@if ($order->payment_status === 'pending')
@php
$waText = rawurlencode("Halo Admin Fersya Shop, saya ingin konfirmasi pembayaran untuk pesanan:\n\n*Nomor Pesanan:* {$order->order_number}\n*Nama:* {$order->guest_name}\n*Total:* Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\nBerikut saya sertakan bukti transfer / scan QRIS.");
$waUrl = "https://wa.me/6281321686115?text={$waText}";
@endphp

<!-- Pembayaran QRIS Card -->
<div class="mt-8 pt-6 border-t border-outline-variant text-center">
<span class="text-xs font-bold text-primary uppercase tracking-widest block mb-3">Scan QRIS Untuk Pembayaran</span>
<div class="bg-white p-4 rounded-2xl border border-outline-variant inline-block shadow-sm mb-4">
<img src="{{ asset('images/qris.png') }}" alt="QRIS Fersya Shop" class="w-56 h-56 mx-auto object-contain"/>
</div>
<p class="text-xs text-on-surface-variant mb-6">Mendukung GoPay, OVO, Dana, ShopeePay, LinkAja, & Semua Bank Transfer (BCA, Mandiri, BRI, BNI).</p>

<a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="w-full bg-[#25D366] text-white py-4 px-6 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-opacity-90 transition-all shadow-md">
<span class="material-symbols-outlined">chat</span>
<span>Konfirmasi Pembayaran via WA</span>
</a>
</div>
@else
<div class="mt-8 pt-6 border-t border-outline-variant text-center">
<div class="bg-primary-fixed/40 p-4 rounded-xl text-primary font-bold text-sm">
✅ Pembayaran telah berhasil dikonfirmasi oleh Admin.
</div>
</div>
@endif
</div>
</div>
</div>
</section>
</x-layouts.app>
