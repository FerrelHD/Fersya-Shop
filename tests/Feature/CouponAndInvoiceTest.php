<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponAndInvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_apply_and_remove_coupon_in_cart(): void
    {
        Coupon::create([
            'code' => 'DISCOUNT10',
            'type' => 'percent',
            'value' => 10,
            'min_spend' => 10000,
            'is_active' => true,
        ]);

        $category = \App\Models\Category::create(['name' => 'Roti', 'slug' => 'roti']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Roti Test',
            'slug' => 'roti-test',
            'description' => 'Test',
            'base_price' => 20000,
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Reguler',
            'price_modifier' => 0,
            'stock' => 10,
            'sku' => 'RT-01',
        ]);

        $this->post(route('cart.store'), ['variant_id' => $variant->id, 'quantity' => 1]);

        $response = $this->post(route('coupon.apply'), ['code' => 'DISCOUNT10']);
        $response->assertSessionHas('coupon_status', 'Kupon berhasil dipasang!');
        $this->assertEquals('DISCOUNT10', session('applied_coupon'));

        $removeResponse = $this->delete(route('coupon.remove'));
        $removeResponse->assertSessionHas('coupon_status', 'Kupon dilepas.');
        $this->assertNull(session('applied_coupon'));
    }

    public function test_can_view_order_invoice(): void
    {
        $order = Order::create([
            'guest_name' => 'Budi Santoso',
            'guest_phone' => '08123456789',
            'order_number' => 'FS-TESTINV1',
            'total_amount' => 50000,
            'shipping_cost' => 0,
            'payment_status' => 'paid',
            'shipping_status' => 'diproses',
        ]);

        $response = $this->get(route('orders.invoice', $order));
        $response->assertStatus(200);
        $response->assertSee('INVOICE');
        $response->assertSee('FS-TESTINV1');
        $response->assertSee('Budi Santoso');
    }
}
