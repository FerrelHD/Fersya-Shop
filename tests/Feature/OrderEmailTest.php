<?php

namespace Tests\Feature;

use App\Mail\OrderCreatedMail;
use App\Mail\OrderShippedMail;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_created_email_is_sent_on_checkout(): void
    {
        Mail::fake();

        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Kopi Arabika',
            'slug' => 'kopi-arabika',
            'base_price' => 50000,
        ]);
        $variant = $product->variants()->create([
            'name' => '200g',
            'price_modifier' => 0,
            'stock' => 10,
            'sku' => 'KPA-01',
        ]);

        $this->post(route('cart.store'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response = $this->post(route('checkout.store'), [
            'guest_name' => 'Budi Santoso',
            'guest_phone' => '08123456789',
            'guest_email' => 'budi@example.com',
            'address' => 'Jl. Merdeka No. 12',
            'city' => 'Jakarta South',
            'province' => 'DKI Jakarta',
            'postal_code' => '12345',
        ]);

        $response->assertRedirect();

        Mail::assertSent(OrderCreatedMail::class, function ($mail) {
            return $mail->hasTo('budi@example.com');
        });
    }

    public function test_order_shipped_emailable_renders_properly(): void
    {
        $order = Order::create([
            'guest_name' => 'Budi Santoso',
            'guest_phone' => '08123456789',
            'guest_email' => 'budi@example.com',
            'order_number' => 'FS-TEST1234',
            'total_amount' => 115000,
            'shipping_cost' => 15000,
            'payment_status' => 'paid',
            'shipping_status' => 'dikirim',
            'shipping_receipt_number' => 'JNE987654321',
        ]);

        $mailable = new OrderShippedMail($order);

        $mailable->assertSeeInHtml('FS-TEST1234');
        $mailable->assertSeeInHtml('JNE987654321');
        $mailable->assertSeeInHtml('Budi Santoso');
    }
}
