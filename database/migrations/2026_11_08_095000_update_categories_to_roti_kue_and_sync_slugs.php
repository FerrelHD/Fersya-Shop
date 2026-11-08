<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Roti Gandum -> Roti & Kue (roti-kue)
        DB::table('categories')
            ->where('slug', 'roti-gandum')
            ->update([
                'name' => 'Roti & Kue',
                'slug' => 'roti-kue',
                'updated_at' => now(),
            ]);

        // 2. Kopi -> Minuman (minuman)
        DB::table('categories')
            ->where('slug', 'kopi')
            ->update([
                'name' => 'Minuman',
                'slug' => 'minuman',
                'updated_at' => now(),
            ]);

        // 3. Teh Herbal -> Frozen Food (frozen-food)
        DB::table('categories')
            ->where('slug', 'teh-herbal')
            ->update([
                'name' => 'Frozen Food',
                'slug' => 'frozen-food',
                'updated_at' => now(),
            ]);

        // Ensure the 3 core categories exist if starting fresh
        $categories = [
            ['name' => 'Roti & Kue', 'slug' => 'roti-kue'],
            ['name' => 'Frozen Food', 'slug' => 'frozen-food'],
            ['name' => 'Minuman', 'slug' => 'minuman'],
        ];

        foreach ($categories as $cat) {
            $exists = DB::table('categories')->where('slug', $cat['slug'])->exists();
            if (! $exists) {
                DB::table('categories')->insert([
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('categories')
            ->where('slug', 'roti-kue')
            ->update([
                'name' => 'Roti Gandum',
                'slug' => 'roti-gandum',
                'updated_at' => now(),
            ]);

        DB::table('categories')
            ->where('slug', 'minuman')
            ->update([
                'name' => 'Kopi',
                'slug' => 'kopi',
                'updated_at' => now(),
            ]);

        DB::table('categories')
            ->where('slug', 'frozen-food')
            ->update([
                'name' => 'Teh Herbal',
                'slug' => 'teh-herbal',
                'updated_at' => now(),
            ]);
    }
};
