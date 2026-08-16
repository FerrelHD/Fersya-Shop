<x-layouts.app title="Keranjang | Fersya Shop">
<section class="px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto py-16">
<h1 class="font-headline-lg text-headline-lg text-primary mb-12">Keranjang Belanja</h1>

@if ($items->isEmpty())
<p class="text-on-surface-variant font-body-md mb-8">Keranjang kamu masih kosong.</p>
<a href="{{ route('katalog.index') }}" class="inline-block bg-primary text-on-primary px-8 py-3 rounded-lg">Belanja Sekarang</a>
@else
<div class="space-y-6 mb-12">
@foreach ($items as $item)
<div class="flex items-center gap-6 bg-surface p-6 rounded-2xl ambient-shadow">
<div class="w-24 h-24 rounded-lg overflow-hidden bg-surface-container shrink-0">
<div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ $item['variant']->product->primaryImage()?->image_path }}')"></div>
</div>
<div class="flex-1">
<h3 class="font-headline-md text-body-lg text-primary">{{ $item['variant']->product->name }}</h3>
<p class="text-on-surface-variant font-body-md">{{ $item['variant']->name }}</p>
<p class="text-primary font-bold">Rp {{ number_format($item['variant']->price(), 0, ',', '.') }}</p>
</div>
<form method="POST" action="{{ route('cart.update', $item['variant']->id) }}" class="flex items-center gap-2">
@csrf
@method('PATCH')
<input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 border border-outline rounded-lg text-center py-2"/>
<button type="submit" class="text-primary underline text-sm">Update</button>
</form>
<form method="POST" action="{{ route('cart.destroy', $item['variant']->id) }}">
@csrf
@method('DELETE')
<button type="submit" class="text-error underline text-sm">Hapus</button>
</form>
<p class="font-bold text-primary w-32 text-right">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
</div>
@endforeach
</div>

<div class="flex justify-between items-center border-t border-outline-variant pt-8">
<span class="font-headline-md text-body-lg text-primary">Subtotal</span>
<span class="font-headline-md text-headline-md text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
</div>
<a href="{{ route('checkout.index') }}" class="mt-8 inline-block bg-primary text-on-primary px-10 py-5 rounded-lg">Lanjut ke Checkout</a>
@endif
</section>
</x-layouts.app>
