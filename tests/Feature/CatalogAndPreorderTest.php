<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogAndPreorderTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_can_filter_by_frozen_food_and_minuman(): void
    {
        $frozenCat = Category::create(['name' => 'Frozen Food', 'slug' => 'frozen-food']);
        $drinksCat = Category::create(['name' => 'Minuman', 'slug' => 'minuman']);

        $frozenProduct = Product::create([
            'category_id' => $frozenCat->id,
            'name' => 'Risoles Mayo Ragout',
            'slug' => 'risoles-mayo-ragout',
            'description' => 'Test Frozen',
            'base_price' => 45000,
            'is_preorder' => true,
            'preorder_info' => 'PO H-1',
        ]);

        $drinkProduct = Product::create([
            'category_id' => $drinksCat->id,
            'name' => 'Kopi Susu Aren',
            'slug' => 'kopi-susu-aren',
            'description' => 'Test Minuman',
            'base_price' => 25000,
            'is_preorder' => false,
        ]);

        // Filter Frozen Food
        $responseFrozen = $this->get(route('katalog.index', ['kategori' => 'frozen-food']));
        $responseFrozen->assertStatus(200);
        $responseFrozen->assertSee('Risoles Mayo Ragout');
        $responseFrozen->assertDontSee('Kopi Susu Aren');

        // Filter Minuman
        $responseDrinks = $this->get(route('katalog.index', ['kategori' => 'minuman']));
        $responseDrinks->assertStatus(200);
        $responseDrinks->assertSee('Kopi Susu Aren');
        $responseDrinks->assertDontSee('Risoles Mayo Ragout');
    }

    public function test_product_detail_displays_preorder_badge_and_lead_time_info(): void
    {
        $frozenCat = Category::create(['name' => 'Frozen Food', 'slug' => 'frozen-food']);

        $product = Product::create([
            'category_id' => $frozenCat->id,
            'name' => 'Pastel Sayur Ayam',
            'slug' => 'pastel-sayur-ayam',
            'description' => 'Pastel beku renyah',
            'base_price' => 48000,
            'is_preorder' => true,
            'preorder_info' => 'PO H-1 (Dibuat fresh & dibekukan higienis)',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Pack 10 Pcs',
            'price_modifier' => 0,
            'stock' => 15,
            'sku' => 'PSA-10',
        ]);

        $response = $this->get(route('products.show', $product));
        $response->assertStatus(200);
        $response->assertSee('Pre-Order');
        $response->assertSee('Dibuat fresh');
        $response->assertSee('Saran Penyajian');
    }

    public function test_cart_displays_preorder_badge_and_whatsapp_button(): void
    {
        $category = Category::create(['name' => 'Roti Gandum', 'slug' => 'roti-gandum']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Roti Gandum Utuh',
            'slug' => 'roti-gandum-utuh',
            'description' => 'Roti fresh',
            'base_price' => 35000,
            'is_preorder' => true,
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Reguler',
            'price_modifier' => 0,
            'stock' => 20,
            'sku' => 'RGU-01',
        ]);

        $this->post(route('cart.store'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);
        $response->assertSee('PO H-1');
        $response->assertSee('Pesan Cepat via WhatsApp');
        $response->assertSee('https://wa.me/6281321686115', false);
    }
}
