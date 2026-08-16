<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{{ $title ?? 'Fersya Shop' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background font-body-md selection:bg-primary-fixed selection:text-on-primary-fixed">
<header class="bg-surface sticky top-0 z-50 w-full transition-all duration-300">
<div class="flex justify-between items-center w-full max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-12 h-20">
<a href="{{ route('home') }}" class="font-headline-md text-headline-md text-primary tracking-tight">Fersya Shop</a>
<nav class="hidden md:flex items-center space-x-8">
<a class="{{ request()->routeIs('katalog.index') && !request('kategori') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-colors duration-200' }} font-body-md text-body-md" href="{{ route('katalog.index') }}">Shop All</a>
<a class="{{ request('kategori') === 'roti-gandum' ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-colors duration-200' }} font-body-md text-body-md" href="{{ route('katalog.index', ['kategori' => 'roti-gandum']) }}">Artisan Bread</a>
<a class="{{ request('kategori') === 'kopi' ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-colors duration-200' }} font-body-md text-body-md" href="{{ route('katalog.index', ['kategori' => 'kopi']) }}">Organic Coffee</a>
<a class="{{ request('kategori') === 'teh-herbal' ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-colors duration-200' }} font-body-md text-body-md" href="{{ route('katalog.index', ['kategori' => 'teh-herbal']) }}">Herbal Tea</a>
<a class="{{ request()->routeIs('orders.search') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-colors duration-200' }} font-body-md text-body-md flex items-center gap-1" href="{{ route('orders.search') }}"><span class="material-symbols-outlined text-lg">search</span> Cek Pesanan</a>
</nav>
<div class="flex items-center space-x-6">
<a href="{{ route('cart.index') }}" class="text-primary hover:opacity-80 transition-opacity flex items-center relative">
<span class="material-symbols-outlined text-2xl">shopping_bag</span>
@if (($cartCount ?? 0) > 0)
<span class="absolute -top-2 -right-2 bg-primary text-on-primary text-[10px] w-4 h-4 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
@endif
</a>
<button id="mobile-menu-btn" class="md:hidden text-primary focus:outline-none">
<span class="material-symbols-outlined text-2xl">menu</span>
</button>
</div>
</div>

<!-- Mobile Navigation Drawer -->
<div id="mobile-menu-overlay" class="fixed inset-0 bg-primary/40 backdrop-blur-sm z-50 hidden transition-opacity duration-300 opacity-0"></div>
<div id="mobile-menu-drawer" class="fixed top-0 right-0 h-full w-4/5 max-w-sm bg-surface shadow-2xl z-50 transform translate-x-full transition-transform duration-300 p-8 flex flex-col justify-between">
<div>
<div class="flex justify-between items-center mb-8 border-b border-outline-variant pb-4">
<span class="font-headline-md text-xl text-primary font-bold">Fersya Shop</span>
<button id="mobile-menu-close" class="text-primary hover:opacity-80">
<span class="material-symbols-outlined text-2xl">close</span>
</button>
</div>
<nav class="flex flex-col space-y-6">
<a class="text-primary font-bold text-lg border-b border-outline-variant pb-2" href="{{ route('katalog.index') }}">Shop All</a>
<a class="text-on-surface-variant hover:text-primary transition-colors text-lg" href="{{ route('katalog.index', ['kategori' => 'roti-gandum']) }}">Artisan Bread</a>
<a class="text-on-surface-variant hover:text-primary transition-colors text-lg" href="{{ route('katalog.index', ['kategori' => 'kopi']) }}">Organic Coffee</a>
<a class="text-on-surface-variant hover:text-primary transition-colors text-lg" href="{{ route('katalog.index', ['kategori' => 'teh-herbal']) }}">Herbal Tea</a>
<a class="text-on-surface-variant hover:text-primary transition-colors text-lg flex items-center gap-2" href="{{ route('orders.search') }}"><span class="material-symbols-outlined text-xl">search</span> Cek Status Pesanan</a>
</nav>
</div>
<div class="border-t border-outline-variant pt-6">
<a href="{{ route('cart.index') }}" class="w-full bg-primary text-on-primary py-4 rounded-lg flex items-center justify-center space-x-2 font-bold">
<span class="material-symbols-outlined">shopping_bag</span>
<span>Keranjang Belanja ({{ $cartCount ?? 0 }})</span>
</a>
</div>
</div>
</header>

<main>
{{ $slot }}
</main>

<footer class="bg-surface-container-low w-full py-section-gap">
<div class="grid grid-cols-1 md:grid-cols-4 gap-grid-gutter px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto">
<div class="md:col-span-1">
<div class="font-headline-md text-headline-md text-primary mb-6">Fersya Shop</div>
<p class="text-on-secondary-container font-body-md leading-relaxed mb-6">
© {{ date('Y') }} Fersya Shop. Consciously curated for your wellbeing.
</p>
</div>
<div>
<h5 class="text-primary font-bold font-body-md mb-6">Explore</h5>
<ul class="space-y-4">
<li><a class="text-on-secondary-container hover:text-primary transition-colors font-body-md" href="{{ route('katalog.index') }}">Shop All</a></li>
<li><a class="text-on-secondary-container hover:text-primary transition-colors font-body-md" href="{{ route('orders.search') }}">Cek Status Pesanan</a></li>
<li><a class="text-on-secondary-container hover:text-primary transition-colors font-body-md" href="{{ route('katalog.index', ['kategori' => 'roti-gandum']) }}">Artisan Bread</a></li>
<li><a class="text-on-secondary-container hover:text-primary transition-colors font-body-md" href="{{ route('katalog.index', ['kategori' => 'kopi']) }}">Organic Coffee</a></li>
<li><a class="text-on-secondary-container hover:text-primary transition-colors font-body-md" href="{{ route('katalog.index', ['kategori' => 'teh-herbal']) }}">Herbal Tea</a></li>
</ul>
</div>
<div>
<h5 class="text-primary font-bold font-body-md mb-6">Customer Care</h5>
<ul class="space-y-4">
<li><button onclick="toggleModal('privacy-modal')" class="text-on-secondary-container hover:text-primary transition-colors font-body-md text-left">Privacy Policy</button></li>
<li><button onclick="toggleModal('shipping-modal')" class="text-on-secondary-container hover:text-primary transition-colors font-body-md text-left">Shipping & Returns</button></li>
</ul>
</div>
<div>
<h5 class="text-primary font-bold font-body-md mb-6">Newsletter</h5>
<form id="newsletter-form" class="flex border-b border-outline pb-2 group focus-within:border-primary">
<input class="bg-transparent border-none focus:ring-0 w-full p-0 text-sm focus:outline-none" placeholder="Email Address" type="email" required/>
<button class="text-primary" type="submit"><span class="material-symbols-outlined">east</span></button>
</form>
<p id="newsletter-status" class="text-xs text-primary font-bold mt-2 hidden">Terima kasih! Email Anda berhasil terdaftar.</p>
</div>
</div>
</footer>

<!-- Modals -->
<div id="privacy-modal" class="fixed inset-0 bg-primary/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
<div class="bg-surface rounded-2xl p-8 max-w-lg w-full max-h-[80vh] overflow-y-auto ambient-shadow">
<h3 class="font-headline-md text-2xl text-primary mb-4">Kebijakan Privasi</h3>
<p class="text-on-surface-variant font-body-md leading-relaxed mb-4">Kami menjaga data pribadi Anda dengan sangat baik. Informasi nama, nomor WhatsApp, dan alamat yang Anda berikan saat checkout hanya digunakan untuk proses pengiriman pesanan Fersya Shop dan tidak akan dijual ke pihak ketiga.</p>
<button onclick="toggleModal('privacy-modal')" class="bg-primary text-on-primary px-6 py-3 rounded-lg w-full">Tutup</button>
</div>
</div>

<div id="shipping-modal" class="fixed inset-0 bg-primary/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
<div class="bg-surface rounded-2xl p-8 max-w-lg w-full max-h-[80vh] overflow-y-auto ambient-shadow">
<h3 class="font-headline-md text-2xl text-primary mb-4">Ketentuan Pengiriman & Pengembalian</h3>
<p class="text-on-surface-variant font-body-md leading-relaxed mb-3"><strong>1. Roti Gandum (Fresh Product):</strong> Mengingat daya tahan roti gandum alami (3 hari suhu ruang), kami menyarankan pengiriman Instant/Sameday.</p>
<p class="text-on-surface-variant font-body-md leading-relaxed mb-4"><strong>2. Garansi Mutu:</strong> Jika produk diterima dalam kondisi rusak/kemasan terbuka, silakan hubungi tim kami via WhatsApp maksimal 1x24 jam setelah pesanan diterima.</p>
<button onclick="toggleModal('shipping-modal')" class="bg-primary text-on-primary px-6 py-3 rounded-lg w-full">Tutup</button>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('mobile-menu-btn');
    const closeBtn = document.getElementById('mobile-menu-close');
    const overlay = document.getElementById('mobile-menu-overlay');
    const drawer = document.getElementById('mobile-menu-drawer');

    function openDrawer() {
        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            drawer.classList.remove('translate-x-full');
        }, 10);
    }

    function closeDrawer() {
        overlay.classList.add('opacity-0');
        drawer.classList.add('translate-x-full');
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 300);
    }

    if (btn) btn.addEventListener('click', openDrawer);
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
    if (overlay) overlay.addEventListener('click', closeDrawer);

    const newsletterForm = document.getElementById('newsletter-form');
    const newsletterStatus = document.getElementById('newsletter-status');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            newsletterStatus.classList.remove('hidden');
            newsletterForm.reset();
        });
    }
});

function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
}
</script>
</body>
</html>
