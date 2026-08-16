<x-layouts.app :title="$product->name . ' | Fersya Shop'">
<section class="px-grid-margin max-w-[1280px] mx-auto py-16">
<div class="flex flex-col md:flex-row gap-16">
<div class="w-full md:w-1/2">
<div class="aspect-square rounded-2xl overflow-hidden bg-surface-container">
<div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ asset($product->primaryImage()?->image_path ?? 'images/bread.png') }}')"></div>
</div>
</div>
<div class="w-full md:w-1/2">
<span class="font-label-sm text-label-sm text-primary uppercase tracking-widest mb-4 block">{{ $product->category->name }}</span>
<h1 class="font-headline-lg text-headline-lg text-primary mb-4">{{ $product->name }}</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-8">{{ $product->description }}</p>

@if (session('status'))
@if ($errors->any())
<div class="mb-6 p-4 rounded-lg bg-error-container text-on-error-container font-body-md">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('cart.store') }}" class="mb-8">
@csrf
@php
    $hasAvailableStock = $product->variants->sum('stock') > 0;
@endphp

@if ($product->variants->count() > 1)
<div class="mb-6">
<span class="font-label-sm text-label-sm uppercase text-on-surface-variant mb-3 block">Pilih Varian</span>
<div class="flex flex-wrap gap-3">
@foreach ($product->variants as $variant)
<label class="border rounded-lg px-5 py-3 cursor-pointer transition-all {{ $variant->stock < 1 ? 'opacity-50 cursor-not-allowed border-outline-variant bg-surface-container' : 'border-outline has-[:checked]:border-primary has-[:checked]:bg-primary has-[:checked]:text-on-primary' }}">
<input type="radio" name="variant_id" value="{{ $variant->id }}" class="hidden" {{ $loop->first && $variant->stock > 0 ? 'checked' : '' }} {{ $variant->stock < 1 ? 'disabled' : '' }}/>
{{ $variant->name }} — Rp {{ number_format($variant->price(), 0, ',', '.') }}
@if ($variant->stock < 1)
<span class="text-xs text-error font-bold ml-1">(Stok Habis)</span>
@else
<span class="text-xs opacity-75 ml-1">(Sisa {{ $variant->stock }})</span>
@endif
</label>
@endforeach
</div>
</div>
@else
@php $singleVariant = $product->variants->first(); @endphp
<input type="hidden" name="variant_id" value="{{ $singleVariant?->id }}"/>
<div class="flex items-center gap-4 mb-8">
<p class="text-primary font-bold text-2xl">Rp {{ number_format($singleVariant?->price() ?? $product->base_price, 0, ',', '.') }}</p>
@if (($singleVariant?->stock ?? 0) > 0)
<span class="text-xs bg-primary-fixed text-on-primary-fixed px-3 py-1 rounded-full font-semibold">Tersisa {{ $singleVariant->stock }} pcs</span>
@else
<span class="text-xs bg-error-container text-on-error-container px-3 py-1 rounded-full font-bold">Stok Habis</span>
@endif
</div>
@endif

<div class="flex gap-4">
<input type="number" name="quantity" value="1" min="1" {{ !$hasAvailableStock ? 'disabled' : '' }} class="w-20 border border-outline rounded-lg text-center py-3 {{ !$hasAvailableStock ? 'bg-surface-container opacity-50' : '' }}"/>
<button type="submit" {{ !$hasAvailableStock ? 'disabled' : '' }} class="flex-1 bg-primary text-on-primary py-3 rounded-lg hover:bg-opacity-90 transition-all {{ !$hasAvailableStock ? 'opacity-50 cursor-not-allowed' : '' }}">
{{ $hasAvailableStock ? 'Tambah ke Keranjang' : 'Stok Habis' }}
</button>
</div>
</form>

@if ($product->shelf_life_info)
<div class="border-t border-outline-variant pt-6 mb-6">
<h3 class="font-headline-md text-body-lg text-primary mb-2">Masa Simpan & Penyimpanan</h3>
<p class="text-on-surface-variant font-body-md">{{ $product->shelf_life_info }}</p>
</div>
@endif

@if ($product->category->slug === 'teh-herbal')
<div class="bg-secondary-container rounded-lg p-6">
<h3 class="font-headline-md text-body-lg text-primary mb-2">Disclaimer</h3>
<p class="text-on-secondary-container font-body-md">Aman diminum saat hari pertama haid. Tidak dianjurkan untuk ibu hamil. Konsultasikan dengan dokter bila ragu.</p>
</div>
@endif
</div>
</div>
</section>

<section class="bg-surface-container-low py-section-gap">
<div class="max-w-[1280px] mx-auto px-grid-margin">
<h2 class="font-headline-lg text-headline-lg text-primary mb-12">Ulasan Pelanggan</h2>

@if (session('review_status'))
<p class="mb-8 font-body-md text-primary">{{ session('review_status') }}</p>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-grid-gutter mb-16">
@forelse ($product->reviews as $review)
<div class="bg-surface p-8 rounded-2xl">
<div class="flex mb-4">
@for ($i = 0; $i < $review->rating; $i++)
<span class="material-symbols-outlined text-primary-container" style="font-variation-settings: 'FILL' 1;">star</span>
@endfor
</div>
<p class="font-body-md text-on-surface-variant">{{ $review->comment }}</p>
</div>
@empty
<p class="text-on-surface-variant font-body-md">Belum ada ulasan untuk produk ini.</p>
@endforelse
</div>

<div class="max-w-lg bg-surface p-8 rounded-2xl">
<h3 class="font-headline-md text-body-lg text-primary mb-4">Sudah pernah beli produk ini?</h3>
<p class="text-on-surface-variant font-body-md mb-6">Masukkan nomor pesanan yang sudah selesai untuk kasih ulasan.</p>
<form method="POST" action="{{ route('reviews.store', $product) }}" class="space-y-4">
@csrf
<input type="text" name="order_number" placeholder="Nomor pesanan" required class="w-full border border-outline rounded-lg py-3 px-4"/>
<select name="rating" required class="w-full border border-outline rounded-lg py-3 px-4">
<option value="5">5 - Sangat puas</option>
<option value="4">4 - Puas</option>
<option value="3">3 - Cukup</option>
<option value="2">2 - Kurang</option>
<option value="1">1 - Tidak puas</option>
</select>
<textarea name="comment" placeholder="Ceritakan pengalamanmu" rows="3" class="w-full border border-outline rounded-lg py-3 px-4"></textarea>
<button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded-lg">Kirim Ulasan</button>
</form>
</div>
</div>
</section>
</x-layouts.app>
