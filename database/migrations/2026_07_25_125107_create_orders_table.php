<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_name')->nullable();
            $table->string('guest_phone')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('order_number')->unique();
            $table->unsignedInteger('total_amount');
            $table->unsignedInteger('shipping_cost')->default(0);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired', 'refunded'])->default('pending');
            $table->string('shipping_status')->default('menunggu_pembayaran');
            $table->string('shipping_receipt_number')->nullable();
            $table->string('snap_token')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('midtrans_transaction_id')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
