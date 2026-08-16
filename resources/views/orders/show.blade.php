<x-layouts.app title="Pesanan {{ $order->order_number }} | Fersya Shop">
<section class="px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto py-16">
@if (session('status'))
<div class="bg-primary-fixed text-on-primary-fixed rounded-lg p-6 mb-8 font-body-md">{{ session('status') }}</div>
@endif

<h1 class="font-headline-lg text-headline-lg text-primary mb-2">Pesanan {{ $order->order_number }}</h1>
<p class="text-on-surface-variant font-body-md mb-12">Status pembayaran: <strong class="text-primary">{{ ucfirst($order->payment_status) }}</strong> · Status pengiriman: <strong class="text-primary">{{ $order->shipping_status }}</strong></p>

<div class="flex flex-col md:flex-row gap-16">
<div class="w-full md:w-2/3 space-y-4">
@foreach ($order->items as $item)
<div class="flex justify-between bg-surface p-6 rounded-2xl ambient-shadow">
<span class="font-body-md text-primary">{{ $item->variant->product->name }} ({{ $item->variant->name }}) x{{ $item->quantity }}</span>
<span class="font-bold text-primary">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
</div>
@endforeach
</div>

<div class="w-full md:w-1/3 bg-surface-container-low p-8 rounded-2xl h-fit">
<h2 class="font-headline-md text-body-lg text-primary mb-6">Total</h2>
<div class="flex justify-between font-body-md mb-2"><span>Ongkir</span><span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
<div class="flex justify-between font-bold text-primary text-lg border-t border-outline-variant pt-4 mt-4"><span>Total</span><span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></div>

@if ($order->payment_status === 'pending')
<form method="POST" action="{{ route('midtrans.simulate', $order) }}" class="mt-8">
@csrf
<button type="submit" class="w-full bg-primary text-on-primary py-4 rounded-lg">Bayar Sekarang (Simulasi)</button>
<p class="text-xs text-on-surface-variant mt-2">Tombol simulasi — Midtrans Snap asli dipasang saat API key sudah tersedia.</p>
</form>
@endif
</div>
</div>
</section>
</x-layouts.app>
