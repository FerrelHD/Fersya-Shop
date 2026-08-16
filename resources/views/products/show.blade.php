<x-layouts.app :title="$product->name . ' | Fersya Shop'">
<section class="px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto py-16">
<div class="flex flex-col md:flex-row gap-8 md:gap-16">
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
<div class="mb-6 p-4 rounded-lg bg-primary-fixed text-on-primary-fixed font-body-md">
    {{ session('status') }}
</div>
@endif

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

<div class="flex gap-3 items-center">
<div class="flex items-center border border-outline rounded-xl overflow-hidden shrink-0 {{ !$hasAvailableStock ? 'opacity-50' : '' }}">
<button type="button" onclick="const i=this.nextElementSibling;i.value=Math.max(1,(+i.value||1)-1)" class="w-10 h-12 text-xl text-primary hover:bg-surface-container transition-colors flex items-center justify-center select-none {{ !$hasAvailableStock ? 'pointer-events-none' : '' }}">−</button>
<input type="number" name="quantity" value="1" min="1" {{ !$hasAvailableStock ? 'disabled' : '' }} class="w-12 h-12 border-x border-outline text-center text-sm font-bold text-primary bg-transparent focus:outline-none appearance-none [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"/>
<button type="button" onclick="const i=this.previousElementSibling;i.value=(+i.value||1)+1" class="w-10 h-12 text-xl text-primary hover:bg-surface-container transition-colors flex items-center justify-center select-none {{ !$hasAvailableStock ? 'pointer-events-none' : '' }}">+</button>
</div>
<button type="submit" {{ !$hasAvailableStock ? 'disabled' : '' }} class="flex-1 bg-primary text-on-primary py-3 rounded-xl font-bold hover:bg-opacity-90 transition-all {{ !$hasAvailableStock ? 'opacity-50 cursor-not-allowed' : '' }}">
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

<section class="bg-surface-container-low py-section-gap" id="review-section">
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-12">
<h2 class="font-headline-lg text-headline-lg text-primary mb-12">Ulasan Pelanggan</h2>

@if (session('review_status'))
<p class="mb-8 font-body-md text-primary font-bold bg-primary-fixed text-on-primary-fixed p-4 rounded-xl">{{ session('review_status') }}</p>
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

<div class="max-w-lg bg-surface p-8 rounded-2xl ambient-shadow">
<h3 class="font-headline-md text-body-lg text-primary mb-4">Sudah pernah beli produk ini?</h3>
<p class="text-on-surface-variant font-body-md mb-6">Masukkan nomor pesanan Anda untuk memberikan ulasan.</p>
<form method="POST" action="{{ route('reviews.store', $product) }}" class="space-y-4">
@csrf
<input type="text" name="order_number" value="{{ request('order', old('order_number')) }}" placeholder="Nomor pesanan (contoh: FS-XXXXXXXX)" required class="w-full border border-outline rounded-xl py-3 px-4 font-body-md text-sm focus:border-primary focus:ring-0 focus:outline-none transition-colors bg-surface-container-low"/>
<div class="relative">
<select name="rating" required class="w-full border border-outline rounded-xl py-3 pl-4 pr-10 font-body-md text-sm text-primary focus:border-primary focus:ring-0 focus:outline-none transition-colors bg-surface-container-low appearance-none cursor-pointer">
<option value="5">⭐⭐⭐⭐⭐ — Sangat Puas</option>
<option value="4">⭐⭐⭐⭐ — Puas</option>
<option value="3">⭐⭐⭐ — Cukup</option>
<option value="2">⭐⭐ — Kurang</option>
<option value="1">⭐ — Tidak Puas</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-secondary pointer-events-none text-base">expand_more</span>
</div>
<textarea name="comment" placeholder="Ceritakan pengalamanmu dengan produk ini..." rows="3" class="w-full border border-outline rounded-xl py-3 px-4 font-body-md text-sm focus:border-primary focus:ring-0 focus:outline-none transition-colors bg-surface-container-low resize-none"></textarea>
<button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded-xl font-bold hover:bg-opacity-90 transition-all w-full sm:w-auto">Kirim Ulasan</button>
</form>
</div>
</div>
</section>
</x-layouts.app>
