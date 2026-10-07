<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_order_search_does_not_leak_other_customers_by_partial_query(): void
    {
        Order::create([
            'guest_name' => 'Budi Santoso',
            'guest_phone' => '081234567890',
            'order_number' => 'FS-SECRET99',
            'total_amount' => 50000,
            'shipping_cost' => 0,
            'payment_status' => 'paid',
            'shipping_status' => 'diproses',
        ]);

        // Partial queries (such as single character "a", partial phone "0812", or name "Budi") must NOT return results
        $response1 = $this->get(route('orders.search', ['q' => 'a']));
        $response1->assertDontSee('FS-SECRET99');

        $response2 = $this->get(route('orders.search', ['q' => 'Budi']));
        $response2->assertDontSee('FS-SECRET99');

        $response3 = $this->get(route('orders.search', ['q' => '0812']));
        $response3->assertDontSee('FS-SECRET99');

        // Exact phone number (>= 10 digits) should find the order
        $response4 = $this->get(route('orders.search', ['q' => '081234567890']));
        $response4->assertSee('FS-SECRET99');
        // Phone number in list view should be masked
        $response4->assertSee('0812****890');
        $response4->assertDontSee('(081234567890)');

        // Exact order number redirects to orders.show
        $response5 = $this->get(route('orders.search', ['q' => 'FS-SECRET99']));
        $response5->assertRedirect(route('orders.show', 'FS-SECRET99'));
    }

    public function test_checkout_handles_stock_gracefully_in_transaction(): void
    {
        $category = Category::create(['name' => 'Roti', 'slug' => 'roti']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Roti Manis',
            'slug' => 'roti-manis',
            'description' => 'Test',
            'base_price' => 15000,
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Cokelat',
            'price_modifier' => 0,
            'stock' => 1,
            'sku' => 'RM-CK-01',
        ]);

        // Add 1 to cart
        $this->post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => 1]);

        // Simulate another user purchasing the last stock right before checkout
        $variant->update(['stock' => 0]);

        $checkoutResponse = $this->post(route('checkout.store'), [
            'guest_name' => 'John Doe',
            'guest_phone' => '081299998888',
            'guest_email' => 'john@example.com',
            'address' => 'Jl. Merdeka No 1',
            'city' => 'Jakarta Selatan',
            'province' => 'DKI Jakarta',
            'postal_code' => '12345',
        ]);

        $checkoutResponse->assertSessionHasErrors('stock');
        $this->assertDatabaseCount('orders', 0);
        $this->assertEquals(0, $variant->fresh()->stock);
    }

    public function test_coupon_endpoint_has_rate_limiting(): void
    {
        // 15 requests are allowed per minute (throttle:15,1)
        for ($i = 0; $i < 15; $i++) {
            $this->post(route('coupon.apply'), ['code' => 'FAKE'.$i]);
        }

        // 16th request within the same minute should be throttled (429 Too Many Requests)
        $response = $this->post(route('coupon.apply'), ['code' => 'FAKE16']);
        $response->assertStatus(429);
    }

    public function test_order_detail_requires_session_access(): void
    {
        $order = Order::create([
            'guest_name' => 'Test User',
            'guest_phone' => '081200001111',
            'order_number' => 'FS-SESSTEST',
            'total_amount' => 50000,
            'shipping_cost' => 0,
            'payment_status' => 'pending',
            'shipping_status' => 'menunggu_pembayaran',
        ]);

        // Akses langsung tanpa session → harus redirect ke cek-pesanan
        $this->get(route('orders.show', 'FS-SESSTEST'))
            ->assertRedirect(route('orders.search'));

        // Akses dengan session yang benar → harus tampil 200
        $this->withSession(['accessible_orders' => ['FS-SESSTEST']])
            ->get(route('orders.show', 'FS-SESSTEST'))
            ->assertStatus(200);

        // Invoice juga harus dilindungi (fresh request, tanpa session)
        $this->flushSession();
        $this->get(route('orders.invoice', 'FS-SESSTEST'))
            ->assertRedirect(route('orders.search'));
    }

    public function test_phone_search_does_not_grant_session_access_to_order_detail(): void
    {
        Order::create([
            'guest_name' => 'Private Customer',
            'guest_phone' => '081299990000',
            'order_number' => 'FS-PRIVATENO',
            'total_amount' => 75000,
            'shipping_cost' => 0,
            'payment_status' => 'paid',
            'shipping_status' => 'diproses',
        ]);

        // Pencarian nomor telepon menampilkan pesanan
        $searchResponse = $this->get(route('orders.search', ['q' => '081299990000']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('FS-PRIVATENO');

        // Tetapi TIDAK boleh otomatis memberi akses session ke orders.show
        $showResponse = $this->get(route('orders.show', 'FS-PRIVATENO'));
        $showResponse->assertRedirect(route('orders.search'));
    }

    public function test_cart_quantity_bound_prevents_excessive_quantities(): void
    {
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Kopi Test',
            'slug' => 'kopi-test',
            'description' => 'Test',
            'base_price' => 30000,
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Biji',
            'price_modifier' => 0,
            'stock' => 500,
            'sku' => 'KP-01',
        ]);

        $response = $this->post(route('cart.store'), [
            'variant_id' => $variant->id,
            'quantity' => 100, // melebihi batas max 99
        ]);

        $response->assertSessionHasErrors('quantity');
    }

    public function test_review_validation_and_approval_workflow(): void
    {
        $category = Category::create(['name' => 'Roti', 'slug' => 'roti-rev']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Roti Ulasan',
            'slug' => 'roti-ulasan',
            'description' => 'Test',
            'base_price' => 20000,
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Reguler',
            'price_modifier' => 0,
            'stock' => 10,
            'sku' => 'RU-01',
        ]);

        $order = Order::create([
            'guest_name' => 'Reviewer',
            'guest_phone' => '081211112222',
            'order_number' => 'FS-REVORDER',
            'total_amount' => 20000,
            'shipping_cost' => 0,
            'payment_status' => 'paid',
            'shipping_status' => 'selesai',
        ]);
        $order->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'price' => 20000,
        ]);

        // Komentar lebih dari 1000 karakter harus ditolak
        $longComment = str_repeat('A', 1001);
        $invalidResponse = $this->post(route('reviews.store', $product), [
            'order_number' => 'FS-REVORDER',
            'rating' => 5,
            'comment' => $longComment,
        ]);
        $invalidResponse->assertSessionHasErrors('comment');

        // Ulasan valid berhasil disimpan dengan status is_approved = false
        $validResponse = $this->post(route('reviews.store', $product), [
            'order_number' => 'FS-REVORDER',
            'rating' => 5,
            'comment' => 'Produk sangat enak dan fresh!',
        ]);
        $validResponse->assertSessionHas('review_status');

        $review = Review::where('order_id', $order->id)->first();
        $this->assertNotNull($review);
        $this->assertFalse((bool) $review->is_approved);
    }
}
