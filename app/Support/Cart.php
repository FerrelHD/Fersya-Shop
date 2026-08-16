<?php

namespace App\Support;

use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class Cart
{
    const SESSION_KEY = 'cart';

    public static function add(int $variantId, int $quantity): void
    {
        $items = Session::get(self::SESSION_KEY, []);
        $items[$variantId] = ($items[$variantId] ?? 0) + $quantity;
        Session::put(self::SESSION_KEY, $items);
    }

    public static function update(int $variantId, int $quantity): void
    {
        $items = Session::get(self::SESSION_KEY, []);
        if ($quantity <= 0) {
            unset($items[$variantId]);
        } else {
            $items[$variantId] = $quantity;
        }
        Session::put(self::SESSION_KEY, $items);
    }

    public static function remove(int $variantId): void
    {
        self::update($variantId, 0);
    }

    public static function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /** @return Collection<int, array{variant: ProductVariant, quantity: int, subtotal: int}> */
    public static function items(): Collection
    {
        $items = Session::get(self::SESSION_KEY, []);
        if (empty($items)) {
            return collect();
        }

        return ProductVariant::with('product.images')
            ->whereIn('id', array_keys($items))
            ->get()
            ->map(fn (ProductVariant $variant) => [
                'variant' => $variant,
                'quantity' => $items[$variant->id],
                'subtotal' => $variant->price() * $items[$variant->id],
            ]);
    }

    public static function count(): int
    {
        return array_sum(Session::get(self::SESSION_KEY, []));
    }

    public static function total(): int
    {
        return self::items()->sum('subtotal');
    }
}
