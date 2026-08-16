<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockAndCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_validates_available_stock(): void
    {
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Kopi Arabika',
            'slug' => 'kopi-arabika',
            'description' => 'Test description',
            'base_price' => 50000,
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Bubuk 200g',
            'price_modifier' => 0,
            'stock' => 3,
            'sku' => 'TEST-001',
        ]);

        // Attempting to add 5 items when only 3 in stock should fail
        $response = $this->post(route('cart.store'), [
            'variant_id' => $variant->id,
            'quantity' => 5,
        ]);

        $response->assertSessionHasErrors('quantity');
        $this->assertEquals(0, session('cart.'.$variant->id, 0));
    }

    public function test_checkout_decrements_variant_stock(): void
    {
        $category = Category::create(['name' => 'Roti', 'slug' => 'roti']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Roti Gandum',
            'slug' => 'roti-gandum',
            'description' => 'Test description',
            'base_price' => 30000,
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Reguler',
            'price_modifier' => 0,
            'stock' => 10,
            'sku' => 'ROTI-001',
        ]);

        // Add 2 items to cart
        $this->post(route('cart.store'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        // Submit checkout
        $response = $this->post(route('checkout.store'), [
            'guest_name' => 'Budi Santoso',
            'guest_phone' => '08123456789',
            'address' => 'Jl. Merdeka No 10',
            'city' => 'Jakarta South',
            'province' => 'DKI Jakarta',
            'postal_code' => '12340',
        ]);

        $response->assertRedirect();

        // Check stock was decremented from 10 to 8
        $this->assertEquals(8, $variant->fresh()->stock);
    }
}
