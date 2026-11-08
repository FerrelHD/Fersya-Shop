<x-layouts.app title="Katalog Produk | Fersya Shop">
<section class="pt-16 pb-12 px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto text-center">
<span class="font-label-sm text-label-sm text-primary tracking-widest uppercase mb-4 block">Semua Produk</span>
<h1 class="font-headline-lg text-headline-lg text-primary mb-6">Katalog Produk</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-xl mx-auto">Pilihan aneka roti & kue lezat, frozen food higienis keluarga, dan minuman segar siap nikmati.</p>
</section>
<section class="px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto mb-16">
<form method="GET" class="flex flex-col md:flex-row items-start md:items-end justify-between gap-6 border-b border-outline-variant/60 pb-3">
<div class="flex items-center gap-6 sm:gap-8 overflow-x-auto w-full md:w-auto -mb-[13px] scrollbar-none">
<button type="submit" name="kategori" value="" class="text-sm font-medium pb-3 border-b-2 whitespace-nowrap transition-colors {{ $activeCategory === '' ? 'border-primary text-primary font-semibold' : 'border-transparent text-stone-500 hover:text-stone-900' }}">Semua</button>
@foreach ($categories as $category)
<button type="submit" name="kategori" value="{{ $category->slug }}" class="text-sm font-medium pb-3 border-b-2 whitespace-nowrap transition-colors {{ $activeCategory === $category->slug ? 'border-primary text-primary font-semibold' : 'border-transparent text-stone-500 hover:text-stone-900' }}">{{ $category->name }}</button>
@endforeach
</div>
<div class="relative w-full md:w-64 mb-1">
<input type="text" name="cari" value="{{ $search }}" placeholder="Cari produk..." class="w-full bg-transparent border border-outline-variant/80 rounded-xl py-2 pl-10 pr-4 text-xs font-body-md text-primary placeholder:text-stone-400 focus:border-primary focus:ring-0 transition-all"/>
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-lg">search</span>
</div>
</form>
</section>
<section class="px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto pb-section-gap">
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
@forelse ($products as $product)
<a href="{{ route('products.show', $product) }}" class="bg-surface p-4 sm:p-5 rounded-2xl ambient-shadow hover-lift flex flex-col justify-between block">
<div>
<div class="relative aspect-[4/3] mb-4 overflow-hidden rounded-xl bg-surface-container">
<div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ asset($product->primaryImage()?->image_path ?? 'images/bread.png') }}')"></div>
</div>
<div class="text-center">
<h4 class="font-headline-md text-base text-primary mb-1 line-clamp-1">{{ $product->name }}</h4>
<div class="flex items-center justify-center gap-1.5 text-sm mb-4">
<span class="text-primary font-bold">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
@if ($product->is_preorder)
<span class="text-stone-300">·</span>
<span class="text-xs text-stone-500 font-normal">Pre-order</span>
@endif
</div>
</div>
</div>
<span class="block w-full border border-primary text-primary py-2.5 rounded-lg text-sm text-center font-semibold hover:bg-primary hover:text-on-primary transition-all">Lihat Produk</span>
</a>
@empty
<p class="col-span-full text-center text-on-surface-variant font-body-md py-16">Produk tidak ditemukan. Coba kata kunci atau kategori lain.</p>
@endforelse
</div>
</section>
</x-layouts.app>
