<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@fersya.test'],
            [
                'name' => 'Admin Fersya',
                'password' => bcrypt('fersya2025'),
                'role' => 'admin',
            ]
        );

        $bread = Category::create(['name' => 'Roti Gandum', 'slug' => 'roti-gandum']);
        $coffee = Category::create(['name' => 'Kopi', 'slug' => 'kopi']);
        $tea = Category::create(['name' => 'Teh Herbal', 'slug' => 'teh-herbal']);

        $breadImg = '/images/bread.png';
        $breadImg2 = '/images/bread.png';
        $breadImg3 = '/images/bread.png';
        $coffeeImg = '/images/coffee.png';
        $coffeeImg2 = '/images/coffee.png';
        $teaImg = '/images/tea.png';
        $teaImg2 = '/images/tea.png';

        $products = [
            [$bread, 'Roti Gandum Utuh', 35000, 'Simpan di suhu ruang maksimal 3 hari atau freezer hingga 2 minggu.', $breadImg, [['Reguler', 0, 40, 'RGU-001']]],
            [$bread, 'Roti Gandum Multigrain', 42000, 'Simpan di suhu ruang maksimal 3 hari atau freezer hingga 2 minggu.', $breadImg2, [['500g', 0, 25, 'RGM-001']]],
            [$bread, 'Roti Gandum Kismis', 38000, 'Simpan di suhu ruang maksimal 3 hari atau freezer hingga 2 minggu.', $breadImg3, [['Reguler', 0, 20, 'RGK-001']]],
            [$coffee, 'Kopi Arabika Gayo', 68000, 'Simpan dalam wadah kedap udara, jauh dari sinar matahari langsung.', $coffeeImg, [['Biji 200g', 0, 30, 'KAG-BIJI'], ['Bubuk 200g', 2000, 30, 'KAG-BUBUK']]],
            [$coffee, 'Kopi Robusta Lampung', 55000, 'Simpan dalam wadah kedap udara, jauh dari sinar matahari langsung.', $coffeeImg2, [['Biji 200g', 0, 30, 'KRL-BIJI']]],
            [$coffee, 'Kopi Arabika Toraja', 72000, 'Simpan dalam wadah kedap udara, jauh dari sinar matahari langsung.', $coffeeImg, [['Bubuk 200g', 0, 20, 'KAT-BUBUK']]],
            [$tea, 'Teh Herbal Pereda Nyeri', 45000, 'Aman diminum saat hari pertama haid, tidak dianjurkan untuk ibu hamil.', $teaImg, [['Kantong 20pcs', 0, 35, 'THP-001']]],
            [$tea, 'Teh Herbal Pelancar Haid', 48000, 'Aman diminum saat hari pertama haid, tidak dianjurkan untuk ibu hamil.', $teaImg2, [['Kantong 20pcs', 0, 35, 'THH-001']]],
            [$tea, 'Teh Herbal Relaksasi Malam', 40000, 'Diminum satu jam sebelum tidur untuk hasil terbaik.', $teaImg2, [['Kantong 20pcs', 0, 25, 'THR-001']]],
        ];

        foreach ($products as [$category, $name, $price, $shelfLife, $image, $variants]) {
            $product = Product::create([
                'category_id' => $category->id,
                'name' => $name,
                'slug' => str($name)->slug(),
                'description' => "Dipilih dengan tangan untuk kualitas terbaik dari {$category->name}.",
                'base_price' => $price,
                'shelf_life_info' => $shelfLife,
            ]);

            $product->images()->create(['image_path' => $image, 'is_primary' => true]);

            foreach ($variants as [$variantName, $modifier, $stock, $sku]) {
                $product->variants()->create([
                    'name' => $variantName,
                    'price_modifier' => $modifier,
                    'stock' => $stock,
                    'sku' => $sku,
                ]);
            }
        }

        \App\Models\Coupon::firstOrCreate(
            ['code' => 'FERSYA10'],
            [
                'type' => 'percent',
                'value' => 10,
                'min_spend' => 50000,
                'is_active' => true,
            ]
        );

        \App\Models\Coupon::firstOrCreate(
            ['code' => 'HEBAT15K'],
            [
                'type' => 'fixed',
                'value' => 15000,
                'min_spend' => 100000,
                'is_active' => true,
            ]
        );
    }
}
