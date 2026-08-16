<?php

namespace App\Support;

class ShippingCalculator
{
    // ponytail: flat rate stub, swap for real Biteship API call when API key is ready
    public static function estimate(string $city): int
    {
        return str_contains(strtolower($city), 'jakarta') ? 12000 : 20000;
    }
}
