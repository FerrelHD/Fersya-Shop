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

<div class="bg-surface p-8 rounded-2xl ambient-shadow space-y-6">
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-outline-variant pb-6">
<div>
<h4 class="font-headline-md text-base text-primary font-bold">Punya Kode Kupon / Promo?</h4>
<p class="text-xs text-on-surface-variant">Coba gunakan <code class="bg-surface-container px-2 py-0.5 rounded text-primary font-bold">FERSYA10</code> (Diskon 10%) atau <code class="bg-surface-container px-2 py-0.5 rounded text-primary font-bold">HEBAT15K</code> (Potongan Rp 15rb)</p>
</div>

@if ($coupon)
<div class="flex items-center gap-3 bg-primary-fixed/40 px-4 py-2 rounded-xl">
<span class="text-xs font-bold text-primary">Kupon Aktif: {{ $coupon->code }} (-Rp {{ number_format($discount, 0, ',', '.') }})</span>
<form method="POST" action="{{ route('coupon.remove') }}">
@csrf
@method('DELETE')
<button type="submit" class="text-xs text-error font-bold hover:underline">Lepas</button>
</form>
</div>
@else
<form method="POST" action="{{ route('coupon.apply') }}" class="flex gap-2 w-full sm:w-auto">
@csrf
<input type="text" name="code" placeholder="Kode promo (contoh: FERSYA10)" required class="uppercase border border-outline rounded-full px-5 py-2.5 text-xs font-semibold text-primary placeholder:normal-case placeholder:text-xs placeholder:text-on-surface-variant/50 focus:border-primary focus:ring-0 shadow-sm"/>
<button type="submit" class="bg-primary text-on-primary font-bold px-6 py-2.5 rounded-full text-xs hover:bg-opacity-90 transition-all shadow-sm">Gunakan</button>
</form>
@endif
</div>

@if (session('coupon_status'))
<p class="text-xs text-primary font-bold bg-primary-fixed/30 p-3 rounded-lg">{{ session('coupon_status') }}</p>
@endif

@if ($errors->has('coupon'))
<p class="text-xs text-error font-bold bg-red-50 p-3 rounded-lg">{{ $errors->first('coupon') }}</p>
@endif

<div class="space-y-3 pt-2 text-sm">
<div class="flex justify-between text-on-surface-variant"><span>Subtotal</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
<div class="flex justify-between text-on-surface-variant"><span>Ongkos Kirim</span><span class="text-primary font-bold">GRATIS</span></div>
@if ($discount > 0)
<div class="flex justify-between text-error font-bold"><span>Diskon Kupon ({{ $coupon->code }})</span><span>-Rp {{ number_format($discount, 0, ',', '.') }}</span></div>
@endif
<div class="flex justify-between items-center font-bold text-primary text-2xl border-t border-outline-variant pt-4">
<span>Total Bayar</span>
<span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
</div>
</div>

<div class="pt-4 flex justify-end">
<a href="{{ route('checkout.index') }}" class="w-full sm:w-auto text-center bg-primary text-on-primary px-10 py-5 rounded-xl font-bold shadow-md hover:bg-opacity-90 transition-all">Lanjut ke Checkout</a>
</div>
</div>
@endif
</section>
</x-layouts.app>
