<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home', [
    'bestSellers' => Product::with('images')->inRandomOrder()->limit(3)->get(),
]))->name('home');

Route::get('/katalog', [CatalogController::class, 'index'])->name('katalog.index');
Route::get('/produk/{product}', [ProductController::class, 'show'])->name('products.show');
Route::post('/produk/{product}/ulasan', [ReviewController::class, 'store'])->name('reviews.store');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang', [CartController::class, 'store'])->name('cart.store');
Route::patch('/keranjang/{variant}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{variant}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/cek-pesanan', [OrderController::class, 'search'])->name('orders.search');
Route::get('/pesanan/{order:order_number}', [OrderController::class, 'show'])->name('orders.show');
