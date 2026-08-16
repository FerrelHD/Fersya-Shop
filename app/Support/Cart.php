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
        Session::forget('applied_coupon');
    }

    public static function applyCoupon(string $code): array
    {
        $code = strtoupper(trim($code));
        $coupon = \App\Models\Coupon::where('code', $code)->where('is_active', true)->first();

        if (! $coupon) {
            return ['success' => false, 'message' => 'Kode kupon tidak ditemukan atau sudah tidak aktif.'];
        }

        $subtotal = self::total();
        if ($subtotal < $coupon->min_spend) {
            return [
                'success' => false,
                'message' => 'Minimal belanja untuk kupon ini adalah Rp ' . number_format($coupon->min_spend, 0, ',', '.'),
            ];
        }

        Session::put('applied_coupon', $coupon->code);
        return ['success' => true, 'message' => 'Kupon berhasil dipasang!'];
    }

    public static function removeCoupon(): void
    {
        Session::forget('applied_coupon');
    }

    public static function coupon(): ?\App\Models\Coupon
    {
        $code = Session::get('applied_coupon');
        if (! $code) return null;

        $coupon = \App\Models\Coupon::where('code', $code)->where('is_active', true)->first();
        if ($coupon && self::total() < $coupon->min_spend) {
            self::removeCoupon();
            return null;
        }

        return $coupon;
    }

    public static function discount(): int
    {
        $coupon = self::coupon();
        return $coupon ? $coupon->calculateDiscount(self::total()) : 0;
    }

    public static function grandTotal(): int
    {
        return max(0, self::total() - self::discount());
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
