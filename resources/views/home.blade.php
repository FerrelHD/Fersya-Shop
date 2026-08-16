<x-layouts.app title="Fersya Shop | Hidup Sehat, Disederhanakan">
<!-- Hero Section (Full Background) -->
<section class="relative min-h-[75vh] lg:min-h-[85vh] flex items-center overflow-hidden bg-surface-container-low">
<!-- Background Image -->
<div class="absolute inset-0 z-0">
<div class="w-full h-full bg-cover bg-center sm:bg-[center_right]" style="background-image: url('{{ asset('images/hero.png') }}')"></div>
</div>
<!-- Desktop Gradient Overlay -->
<div class="absolute inset-0 hero-gradient hidden md:block z-0"></div>
<!-- Mobile Overlay for Readability -->
<div class="absolute inset-0 bg-surface/85 backdrop-blur-[2px] md:hidden z-0"></div>

<!-- Hero Content -->
<div class="relative z-10 max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-12 w-full py-16 lg:py-24">
<div class="max-w-xl lg:max-w-2xl reveal space-y-6">
<span class="font-label-sm text-label-sm text-primary tracking-widest uppercase block">Premium Health-Conscious Retail</span>
<h1 class="font-display-lg text-4xl sm:text-5xl lg:text-6xl text-primary font-bold leading-tight">Hidup sehat, disederhanakan</h1>
<p class="font-body-lg text-lg sm:text-xl text-on-surface-variant leading-relaxed">Bahan jujur untuk hidup yang lebih baik. Kami mengkurasi roti gandum, kopi pilihan, dan ramuan herbal terbaik untuk Anda.</p>
<div class="flex flex-col sm:flex-row gap-4 pt-4">
<a class="bg-primary text-on-primary px-8 py-4 rounded-xl font-body-md text-center hover:bg-opacity-90 transition-all ambient-shadow" href="{{ route('katalog.index') }}">Belanja Sekarang</a>
<a class="border border-outline text-primary px-8 py-4 rounded-xl font-body-md text-center hover:bg-surface-container transition-all" href="{{ route('katalog.index') }}">Lihat Katalog</a>
</div>
</div>
</div>
</section>

<!-- Product Categories -->
<section class="py-10 sm:py-section-gap px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto">
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-grid-gutter">
<a href="{{ route('katalog.index', ['kategori' => 'roti-gandum']) }}" class="group cursor-pointer reveal block">
<div class="aspect-[16/9] sm:aspect-[4/5] bg-surface-container mb-4 sm:mb-8 overflow-hidden rounded-2xl">
<div class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform duration-700" style="background-image: url('{{ asset('images/bread.png') }}')"></div>
</div>
<div class="flex items-center mb-2 sm:mb-4">
<span class="material-symbols-outlined text-primary-container mr-3 text-2xl">bakery_dining</span>
<h3 class="font-headline-md text-headline-md text-primary">Roti Gandum</h3>
</div>
<p class="text-secondary font-body-md mb-4 sm:mb-6 text-sm sm:text-base">Dibuat dengan ragi alami dan gandum utuh organik tanpa pengawet.</p>
<span class="text-primary font-semibold flex items-center group-hover:gap-2 transition-all text-sm sm:text-base">Jelajahi <span class="material-symbols-outlined ml-1 text-base sm:text-xl">arrow_forward</span></span>
</a>
<a href="{{ route('katalog.index', ['kategori' => 'kopi']) }}" class="group cursor-pointer reveal block" style="transition-delay: 100ms;">
<div class="aspect-[16/9] sm:aspect-[4/5] bg-surface-container mb-4 sm:mb-8 overflow-hidden rounded-2xl">
<div class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform duration-700" style="background-image: url('{{ asset('images/coffee.png') }}')"></div>
</div>
<div class="flex items-center mb-2 sm:mb-4">
<span class="material-symbols-outlined text-primary-container mr-3 text-2xl">coffee</span>
<h3 class="font-headline-md text-headline-md text-primary">Kopi</h3>
</div>
<p class="text-secondary font-body-md mb-4 sm:mb-6 text-sm sm:text-base">Biji kopi pilihan dari dataran tinggi Indonesia, disangrai dengan presisi.</p>
<span class="text-primary font-semibold flex items-center group-hover:gap-2 transition-all text-sm sm:text-base">Jelajahi <span class="material-symbols-outlined ml-1 text-base sm:text-xl">arrow_forward</span></span>
</a>
<a href="{{ route('katalog.index', ['kategori' => 'teh-herbal']) }}" class="group cursor-pointer reveal block" style="transition-delay: 200ms;">
<div class="aspect-[16/9] sm:aspect-[4/5] bg-surface-container mb-4 sm:mb-8 overflow-hidden rounded-2xl">
<div class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform duration-700" style="background-image: url('{{ asset('images/tea.png') }}')"></div>
</div>
<div class="flex items-center mb-2 sm:mb-4">
<span class="material-symbols-outlined text-primary-container mr-3 text-2xl">eco</span>
<h3 class="font-headline-md text-headline-md text-primary">Teh Herbal</h3>
</div>
<p class="text-secondary font-body-md mb-4 sm:mb-6 text-sm sm:text-base">Ramuan bunga dan herba untuk menenangkan pikiran dan raga.</p>
<span class="text-primary font-semibold flex items-center group-hover:gap-2 transition-all text-sm sm:text-base">Jelajahi <span class="material-symbols-outlined ml-1 text-base sm:text-xl">arrow_forward</span></span>
</a>
</div>
</section>

<!-- Best Sellers Section -->
<section class="bg-surface-container-low py-section-gap overflow-hidden">
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-12">
<div class="flex justify-between items-end mb-16 reveal">
<div>
<span class="font-label-sm text-label-sm text-primary uppercase tracking-[0.2em] mb-4 block">Pilihan Terfavorit</span>
<h2 class="font-headline-lg text-headline-lg text-primary">Produk Terbaik Kami</h2>
</div>
<a class="hidden md:block text-primary font-semibold border-b border-primary" href="{{ route('katalog.index') }}">Lihat Semua Produk</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-grid-gutter">
@foreach ($bestSellers as $product)
<a href="{{ route('products.show', $product) }}" class="bg-surface p-6 rounded-2xl ambient-shadow reveal hover-lift block" style="transition-delay: {{ $loop->index * 100 }}ms;">
<div class="aspect-square mb-6 overflow-hidden rounded-xl">
<div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ asset($product->primaryImage()?->image_path ?? 'images/bread.png') }}')"></div>
</div>
<div class="text-center">
<h4 class="font-headline-md text-body-lg text-primary mb-2">{{ $product->name }}</h4>
<p class="text-primary font-bold mb-6">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
<span class="block w-full border border-primary text-primary py-3 rounded-lg hover:bg-primary hover:text-on-primary transition-all">Lihat Produk</span>
</div>
</a>
@endforeach
</div>
</div>
</section>

<!-- Story/Source Section -->
<section class="py-section-gap px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto">
<div class="flex flex-col md:flex-row items-center gap-8 md:gap-16">
<div class="w-full md:w-1/2 reveal">
<div class="relative">
<div class="aspect-[16/9] sm:aspect-[4/5] bg-surface-container-high rounded-3xl overflow-hidden">
<div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ asset('images/story.png') }}')"></div>
</div>
<div class="absolute -bottom-8 -right-8 bg-primary-container text-on-primary-container p-8 rounded-2xl ambient-shadow hidden lg:block max-w-[240px]">
<p class="font-headline-md text-body-md italic mb-0">"Kualitas yang kami bawa berakar dari ketulusan para petani lokal."</p>
</div>
</div>
</div>
<div class="w-full md:w-1/2 reveal" style="transition-delay: 200ms;">
<h2 class="font-headline-lg text-headline-lg text-primary mb-8 leading-tight">Dari petani lokal, untuk ritual pagimu</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-8">Setiap produk di Fersya Shop dipilih dengan tangan. Kami bekerja langsung dengan petani yang mempraktikkan regenerasi tanah, memastikan apa yang Anda konsumsi tidak hanya menyehatkan tubuh, tetapi juga menghormati alam.</p>
<ul class="space-y-6 mb-12">
<li class="flex items-start">
<span class="material-symbols-outlined text-primary-container mr-4 mt-1">check_circle</span>
<div>
<span class="font-semibold text-primary block">100% Organik & Alami</span>
<span class="text-on-surface-variant">Tanpa bahan kimia tambahan atau pengawet sintetis.</span>
</div>
</li>
<li class="flex items-start">
<span class="material-symbols-outlined text-primary-container mr-4 mt-1">handshake</span>
<div>
<span class="font-semibold text-primary block">Kemitraan Adil</span>
<span class="text-on-surface-variant">Memberdayakan produsen kecil dengan harga yang pantas.</span>
</div>
</li>
</ul>
<a class="inline-block bg-primary text-on-primary px-10 py-5 rounded-xl hover:bg-opacity-90 transition-all font-body-md text-body-md" href="{{ route('katalog.index') }}">Pelajari Visi Kami</a>
</div>
</div>
</section>

<!-- Testimonials -->
<section class="bg-surface py-section-gap">
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-12">
<div class="text-center mb-16 reveal">
<h2 class="font-headline-lg text-headline-lg text-primary mb-4">Cerita dari pelanggan</h2>
<div class="h-1 w-20 bg-primary-container mx-auto"></div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-grid-gutter">
<div class="bg-surface-container-low p-6 sm:p-12 rounded-3xl reveal">
<div class="flex mb-6">
@for ($i = 0; $i < 5; $i++)
<span class="material-symbols-outlined text-primary-container" style="font-variation-settings: 'FILL' 1;">star</span>
@endfor
</div>
<p class="font-headline-md text-body-lg italic text-primary mb-8 leading-relaxed">"Roti gandumnya luar biasa lembut tapi tetap berserat. Menjadi andalan sarapan keluarga saya setiap pagi sekarang. Wanginya pun sangat alami."</p>
<div class="flex items-center">
<div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center mr-4">
<span class="font-bold text-on-primary-fixed">S</span>
</div>
<div>
<span class="font-bold text-primary block">Sari</span>
<span class="text-sm text-secondary">Jakarta Selatan</span>
</div>
</div>
</div>
<div class="bg-surface-container-low p-6 sm:p-12 rounded-3xl reveal" style="transition-delay: 150ms;">
<div class="flex mb-6">
@for ($i = 0; $i < 5; $i++)
<span class="material-symbols-outlined text-primary-container" style="font-variation-settings: 'FILL' 1;">star</span>
@endfor
</div>
<p class="font-headline-md text-body-lg italic text-primary mb-8 leading-relaxed">"Teh herbal pereda nyerinya sangat membantu saat saya sedang tidak enak badan. Kemasannya sangat premium, cocok untuk kado juga."</p>
<div class="flex items-center">
<div class="w-12 h-12 rounded-full bg-tertiary-fixed flex items-center justify-center mr-4">
<span class="font-bold text-on-tertiary-fixed">N</span>
</div>
<div>
<span class="font-bold text-primary block">Nadia</span>
<span class="text-sm text-secondary">Bandung</span>
</div>
</div>
</div>
</div>
</div>
</section>

<!-- FAQ -->
<section class="py-section-gap px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto">
<div class="max-w-3xl mx-auto reveal">
<h2 class="font-headline-lg text-headline-lg text-primary mb-12 text-center">Pertanyaan Umum</h2>
<div class="space-y-4">
<div class="border-b border-surface-variant py-6">
<button class="flex justify-between items-center w-full text-left group" data-faq-toggle>
<span class="font-headline-md text-body-lg text-primary group-hover:text-primary-container transition-colors">Apakah pengiriman bisa di hari yang sama?</span>
<span class="material-symbols-outlined transform transition-transform duration-300">expand_more</span>
</button>
<div class="hidden mt-4 text-on-surface-variant font-body-md">
Ya, untuk wilayah JABODETABEK pengiriman bisa dilakukan via kurir instan/sameday jika pesanan masuk sebelum pukul 14:00 WIB.
</div>
</div>
<div class="border-b border-surface-variant py-6">
<button class="flex justify-between items-center w-full text-left group" data-faq-toggle>
<span class="font-headline-md text-body-lg text-primary group-hover:text-primary-container transition-colors">Bagaimana cara penyimpanan roti gandum?</span>
<span class="material-symbols-outlined transform transition-transform duration-300">expand_more</span>
</button>
<div class="hidden mt-4 text-on-surface-variant font-body-md">
Simpan dalam wadah kedap udara di suhu ruang maksimal 3 hari, atau simpan di dalam freezer hingga 2 minggu untuk menjaga kesegaran terbaik.
</div>
</div>
<div class="border-b border-surface-variant py-6">
<button class="flex justify-between items-center w-full text-left group" data-faq-toggle>
<span class="font-headline-md text-body-lg text-primary group-hover:text-primary-container transition-colors">Apakah produk ini aman untuk ibu hamil?</span>
<span class="material-symbols-outlined transform transition-transform duration-300">expand_more</span>
</button>
<div class="hidden mt-4 text-on-surface-variant font-body-md">
Roti dan kopi kami aman dikonsumsi. Untuk varian teh herbal tertentu, kami menyarankan konsultasi dengan dokter terlebih dahulu.
</div>
</div>
</div>
</div>
</section>
</x-layouts.app>
