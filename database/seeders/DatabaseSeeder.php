<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        if (app()->environment('local', 'testing')) {
            User::firstOrCreate(
                ['email' => env('ADMIN_EMAIL', 'admin@fersya.test')],
                [
                    'name' => 'Admin Fersya',
                    'password' => bcrypt(env('ADMIN_PASSWORD', 'fersya2025')),
                    'role' => 'admin',
                ]
            );
        }

        $bread = Category::firstOrCreate(['slug' => 'roti-gandum'], ['name' => 'Roti Gandum']);
        $frozen = Category::firstOrCreate(['slug' => 'frozen-food'], ['name' => 'Frozen Food']);
        $drinks = Category::firstOrCreate(['slug' => 'minuman'], ['name' => 'Minuman']);

        $breadImg = '/images/bread.png';
        $breadImg2 = '/images/bread.png';
        $breadImg3 = '/images/bread.png';
        $coffeeImg = '/images/coffee.png';
        $coffeeImg2 = '/images/coffee.png';
        $frozenImg = '/images/tea.png';
        $frozenImg2 = '/images/tea.png';

        $products = [
            [$bread, 'Roti Gandum Utuh', 35000, 'Simpan di suhu ruang maksimal 3 hari atau freezer hingga 2 minggu.', $breadImg, true, 'PO H-1 (Freshly baked, pesan hari ini dikirim besok)', [['Reguler', 0, 40, 'RGU-001']]],
            [$bread, 'Roti Gandum Multigrain', 42000, 'Simpan di suhu ruang maksimal 3 hari atau freezer hingga 2 minggu.', $breadImg2, true, 'PO H-1 (Freshly baked, pesan hari ini dikirim besok)', [['500g', 0, 25, 'RGM-001']]],
            [$bread, 'Roti Gandum Kismis', 38000, 'Simpan di suhu ruang maksimal 3 hari atau freezer hingga 2 minggu.', $breadImg3, true, 'PO H-1 (Freshly baked, pesan hari ini dikirim besok)', [['Reguler', 0, 20, 'RGK-001']]],
            [$frozen, 'Risoles Mayo Ragout', 45000, 'Simpan beku di freezer (-18°C) tahan hingga 1 bulan. Goreng saat setengah beku dengan api sedang.', $frozenImg, true, 'PO H-1 (Dibuat fresh & dibekukan higienis)', [['Pack 10 Pcs', 0, 30, 'RMR-010']]],
            [$frozen, 'Cireng Crispy Bumbu Rujak', 30000, 'Simpan di freezer hingga 1 bulan. Goreng garing dan nikmati dengan bumbu rujak khas.', $frozenImg2, true, 'PO H-1 (Dibuat fresh & dibekukan higienis)', [['Pack 20 Pcs', 0, 35, 'CCB-020']]],
            [$frozen, 'Pastel Sayur & Ayam Beku', 48000, 'Simpan beku di freezer hingga 3 minggu. Goreng hingga renyah keemasan.', $frozenImg, true, 'PO H-1 (Dibuat fresh & dibekukan higienis)', [['Pack 10 Pcs', 0, 25, 'PSA-010']]],
            [$drinks, 'Kopi Arabika Gayo', 68000, 'Simpan dalam wadah kedap udara, jauh dari sinar matahari langsung.', $coffeeImg, false, null, [['Biji 200g', 0, 30, 'KAG-BIJI'], ['Bubuk 200g', 2000, 30, 'KAG-BUBUK']]],
            [$drinks, 'Kopi Susu Gula Aren Fresh', 25000, 'Simpan di chiller/kulkas, nikmati dalam 3 hari. Kocok sebelum diminum.', $coffeeImg2, false, null, [['Botol 500ml', 0, 25, 'KSG-500'], ['Botol 1 Liter', 22000, 15, 'KSG-1000']]],
            [$drinks, 'Matcha Latte Artisan', 28000, 'Simpan di chiller/kulkas, nikmati dalam 3 hari. Menggunakan matcha murni pilihan.', $coffeeImg, false, null, [['Botol 500ml', 0, 20, 'MLA-500']]],
        ];

        foreach ($products as [$category, $name, $price, $shelfLife, $image, $isPreorder, $preorderInfo, $variants]) {
            $slug = str($name)->slug();
            $product = Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'name' => $name,
                    'description' => "Dipilih dengan tangan untuk kualitas terbaik dari {$category->name}.",
                    'base_price' => $price,
                    'shelf_life_info' => $shelfLife,
                    'is_preorder' => $isPreorder,
                    'preorder_info' => $preorderInfo,
                ]
            );

            if (! $product->images()->exists()) {
                $product->images()->create(['image_path' => $image, 'is_primary' => true]);
            }

            foreach ($variants as [$variantName, $modifier, $stock, $sku]) {
                $product->variants()->firstOrCreate(
                    ['sku' => $sku],
                    [
                        'name' => $variantName,
                        'price_modifier' => $modifier,
                        'stock' => $stock,
                    ]
                );
            }
        }

        Coupon::firstOrCreate(
            ['code' => 'FERSYA10'],
            [
                'type' => 'percent',
                'value' => 10,
                'min_spend' => 50000,
                'is_active' => true,
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'HEBAT15K'],
            [
                'type' => 'fixed',
                'value' => 15000,
                'min_spend' => 100000,
                'is_active' => true,
            ]
        );

        Setting::set('announcement_active', '1');
        Setting::set('announcement_text', '🍞 Roti Gandum & Frozen Food (PO) · ☕ Minuman Segar (Ready) · Diskon 10% Kode: FERSYA10');
    }
}
