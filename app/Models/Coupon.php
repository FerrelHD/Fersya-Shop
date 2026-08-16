<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'type', 'value', 'min_spend', 'is_active'])]
class Coupon extends Model
{
    public function calculateDiscount(int $subtotal): int
    {
        if ($subtotal < $this->min_spend) {
            return 0;
        }

        if ($this->type === 'percent') {
            return (int) round(($subtotal * $this->value) / 100);
        }

        return min($this->value, $subtotal);
    }
}
