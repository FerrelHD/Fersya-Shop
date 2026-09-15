<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SitemapController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home', [
    'bestSellers' => Product::with('images')->latest()->limit(3)->get(),
]))->name('home');

Route::get('/katalog', [CatalogController::class, 'index'])->name('katalog.index');
Route::get('/produk/{product}', [ProductController::class, 'show'])->name('products.show');
Route::post('/produk/{product}/ulasan', [ReviewController::class, 'store'])->middleware('throttle:5,1')->name('reviews.store');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang', [CartController::class, 'store'])->name('cart.store');
Route::patch('/keranjang/{variant}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{variant}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/kupon', [CartController::class, 'applyCoupon'])->middleware('throttle:15,1')->name('coupon.apply');
Route::delete('/kupon', [CartController::class, 'removeCoupon'])->name('coupon.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('checkout.store');

Route::get('/cek-pesanan', [OrderController::class, 'search'])->middleware('throttle:30,1')->name('orders.search');
Route::get('/pesanan/{order:order_number}', [OrderController::class, 'show'])->middleware('throttle:30,1')->name('orders.show');
Route::get('/pesanan/{order:order_number}/invoice', [OrderController::class, 'invoice'])->middleware('throttle:20,1')->name('orders.invoice');

Route::post('/newsletter', [NewsletterController::class, 'store'])->middleware('throttle:5,1')->name('newsletter.store');

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
