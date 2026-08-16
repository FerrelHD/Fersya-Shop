<x-layouts.app title="Katalog Produk | Fersya Shop">
<section class="pt-16 pb-12 px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto text-center">
<span class="font-label-sm text-label-sm text-primary tracking-widest uppercase mb-4 block">Semua Produk</span>
<h1 class="font-headline-lg text-headline-lg text-primary mb-6">Katalog Produk</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-xl mx-auto">Kurasi roti gandum, kopi, dan teh herbal terbaik kami — dipilih untuk hidup yang lebih sehat.</p>
</section>
<section class="px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto mb-16">
<form method="GET" class="flex flex-col md:flex-row items-center justify-between gap-6 border-b border-outline-variant pb-8">
<div class="flex flex-wrap gap-3">
<button type="submit" name="kategori" value="" class="font-label-sm text-label-sm uppercase px-6 py-3 rounded-full transition-all {{ $activeCategory === '' ? 'bg-primary text-on-primary' : 'bg-secondary-container text-primary' }}">Semua</button>
@foreach ($categories as $category)
<button type="submit" name="kategori" value="{{ $category->slug }}" class="font-label-sm text-label-sm uppercase px-6 py-3 rounded-full transition-all {{ $activeCategory === $category->slug ? 'bg-primary text-on-primary' : 'bg-secondary-container text-primary' }}">{{ $category->name }}</button>
@endforeach
</div>
<div class="relative w-full md:w-72">
<input type="text" name="cari" value="{{ $search }}" placeholder="Cari produk..." class="w-full bg-transparent border border-outline rounded-lg py-3 pl-12 pr-4 font-body-md text-body-md focus:border-primary focus:ring-0 transition-all"/>
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-secondary">search</span>
</div>
</form>
</section>
<section class="px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto pb-section-gap">
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-grid-gutter">
@forelse ($products as $product)
<a href="{{ route('products.show', $product) }}" class="bg-surface p-6 rounded-2xl ambient-shadow hover-lift block">
<div class="aspect-square mb-6 overflow-hidden rounded-xl">
<div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ asset($product->primaryImage()?->image_path ?? 'images/bread.png') }}')"></div>
</div>
<div class="text-center">
<h4 class="font-headline-md text-body-lg text-primary mb-2">{{ $product->name }}</h4>
<p class="text-primary font-bold mb-6">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
<span class="block w-full border border-primary text-primary py-3 rounded-lg hover:bg-primary hover:text-on-primary transition-all">Lihat Produk</span>
</div>
</a>
@empty
<p class="col-span-full text-center text-on-surface-variant font-body-md py-16">Produk tidak ditemukan. Coba kata kunci atau kategori lain.</p>
@endforelse
</div>
</section>
</x-layouts.app>
