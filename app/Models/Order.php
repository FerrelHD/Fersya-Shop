<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'user_id', 'guest_name', 'guest_phone', 'guest_email', 'order_number',
    'total_amount', 'shipping_cost', 'payment_status', 'shipping_status',
    'shipping_receipt_number', 'snap_token', 'payment_url', 'midtrans_transaction_id',
])]
class Order extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingAddress(): HasOne
    {
        return $this->hasOne(ShippingAddress::class);
    }

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }
}
