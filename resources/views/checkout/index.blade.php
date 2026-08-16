<x-layouts.app title="Checkout | Fersya Shop">
<section class="px-4 sm:px-6 lg:px-12 max-w-[1400px] mx-auto py-16">
<h1 class="font-headline-lg text-headline-lg text-primary mb-12">Checkout</h1>
<div class="flex flex-col md:flex-row gap-16">
<form method="POST" action="{{ route('checkout.store') }}" class="w-full md:w-2/3 space-y-6">
@csrf
<h2 class="font-headline-md text-body-lg text-primary mb-2">Data Penerima</h2>
<input type="text" name="guest_name" value="{{ old('guest_name') }}" placeholder="Nama Lengkap" required class="w-full border border-outline rounded-lg py-3 px-4"/>
<input type="text" name="guest_phone" value="{{ old('guest_phone') }}" placeholder="Nomor WhatsApp" required class="w-full border border-outline rounded-lg py-3 px-4"/>
<input type="email" name="guest_email" value="{{ old('guest_email') }}" placeholder="Email (opsional)" class="w-full border border-outline rounded-lg py-3 px-4"/>

<h2 class="font-headline-md text-body-lg text-primary mb-2 pt-4">Alamat Pengiriman</h2>
<textarea name="address" placeholder="Alamat lengkap" required rows="3" class="w-full border border-outline rounded-lg py-3 px-4">{{ old('address') }}</textarea>
<div class="grid grid-cols-2 gap-4">
<input type="text" name="city" value="{{ old('city') }}" placeholder="Kota" required class="border border-outline rounded-lg py-3 px-4"/>
<input type="text" name="province" value="{{ old('province') }}" placeholder="Provinsi" required class="border border-outline rounded-lg py-3 px-4"/>
</div>
<input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="Kode Pos" required class="w-full border border-outline rounded-lg py-3 px-4"/>

@if ($errors->any())
<div class="text-error font-body-md">
<ul>
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<button type="submit" class="bg-primary text-on-primary px-10 py-5 rounded-lg w-full">Buat Pesanan</button>
</form>

<div class="w-full md:w-1/3 bg-surface-container-low p-8 rounded-2xl h-fit">
<h2 class="font-headline-md text-body-lg text-primary mb-6">Ringkasan Pesanan</h2>
@foreach ($items as $item)
<div class="flex justify-between font-body-md mb-3">
<span>{{ $item['variant']->product->name }} ({{ $item['variant']->name }}) x{{ $item['quantity'] }}</span>
<span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
</div>
@endforeach
<div class="border-t border-outline-variant mt-4 pt-4 flex justify-between text-sm text-on-surface-variant">
<span>Subtotal</span>
<span>Rp {{ number_format($total, 0, ',', '.') }}</span>
</div>
<div class="flex justify-between items-center mt-2">
<span class="text-sm text-on-surface-variant">Ongkos Kirim</span>
<span class="text-xs font-bold text-primary bg-primary-fixed/40 px-2 py-0.5 rounded-full">GRATIS</span>
</div>
@if ($discount > 0)
<div class="flex justify-between items-center mt-2 text-sm text-error font-bold">
<span>Diskon Kupon ({{ $coupon->code }})</span>
<span>-Rp {{ number_format($discount, 0, ',', '.') }}</span>
</div>
@endif
<div class="border-t border-outline-variant mt-3 pt-3 flex justify-between font-bold text-primary text-xl">
<span>Total Bayar</span>
<span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
</div>
</div>
</div>
</section>
</x-layouts.app>
