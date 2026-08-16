<x-layouts.app title="Cek Status Pesanan | Fersya Shop">
<section class="pt-16 pb-12 px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto text-center">
<span class="font-label-sm text-label-sm text-primary tracking-widest uppercase mb-4 block">Lacak & Riwayat Belanja</span>
<h1 class="font-headline-lg text-headline-lg text-primary mb-6">Cek Status Pesanan</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-xl mx-auto mb-10">Masukkan nomor pesanan (contoh: FS-XXXXXXXX) atau nomor WhatsApp Anda untuk melihat riwayat dan status pengiriman.</p>

<form method="GET" action="{{ route('orders.search') }}" class="max-w-xl mx-auto flex flex-col sm:flex-row gap-4 mb-16">
<div class="relative flex-1">
<input type="text" name="q" value="{{ $query }}" placeholder="Nomor pesanan / Nomor WhatsApp" required class="w-full bg-surface border border-outline rounded-xl py-4 pl-12 pr-4 font-body-md text-body-md focus:border-primary focus:ring-0 shadow-sm"/>
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-secondary">search</span>
</div>
<button type="submit" class="bg-primary text-on-primary px-8 py-4 rounded-xl font-bold hover:bg-opacity-90 transition-all ambient-shadow">Cek Pesanan</button>
</form>
</section>

@if ($query !== '')
<section class="px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto pb-section-gap">
<h2 class="font-headline-md text-body-lg text-primary mb-8 border-b border-outline-variant pb-4">Hasil Pencarian untuk "{{ $query }}"</h2>

@if ($orders->isEmpty())
<div class="bg-surface p-12 rounded-2xl text-center max-w-lg mx-auto ambient-shadow">
<span class="material-symbols-outlined text-5xl text-secondary mb-4">search_off</span>
<h3 class="font-headline-md text-body-lg text-primary mb-2">Pesanan Tidak Ditemukan</h3>
<p class="text-on-surface-variant font-body-md mb-6">Pastikan nomor pesanan atau nomor telepon yang Anda masukkan sudah benar.</p>
<a href="{{ route('katalog.index') }}" class="inline-block bg-primary text-on-primary px-6 py-3 rounded-lg font-bold">Mulai Belanja</a>
</div>
@else
<div class="space-y-6 max-w-4xl mx-auto">
@foreach ($orders as $order)
<div class="bg-surface p-6 sm:p-8 rounded-2xl ambient-shadow flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
<div class="space-y-2">
<div class="flex flex-wrap items-center gap-2">
<span class="font-headline-md text-lg text-primary font-bold">{{ $order->order_number }}</span>
@if ($order->payment_status === 'paid')
<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
<span class="material-symbols-outlined text-xs text-emerald-600">check_circle</span>
<span>Lunas</span>
</span>
@else
<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
<span class="material-symbols-outlined text-xs text-amber-600">schedule</span>
<span>Belum Bayar</span>
</span>
@endif
<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200/60">
<span class="material-symbols-outlined text-xs text-sky-600">local_shipping</span>
<span>{{ ucwords(str_replace('_', ' ', $order->shipping_status)) }}</span>
</span>
</div>
<p class="text-on-surface-variant text-sm">Pembeli: <strong>{{ $order->guest_name }}</strong> ({{ $order->guest_phone }}) · Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
<p class="text-primary font-bold text-base">Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
</div>
<a href="{{ route('orders.show', $order) }}" class="w-full sm:w-auto text-center border border-primary text-primary px-6 py-3 rounded-xl font-bold hover:bg-primary hover:text-on-primary transition-all">Lihat Detail & QRIS</a>
</div>
@endforeach
</div>
@endif
</section>
@endif
</x-layouts.app>
