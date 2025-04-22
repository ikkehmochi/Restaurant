<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Appetizers',
            'Main Course',
            'Desserts',
            'Beverages',
            'Special Items'
        ];

        foreach ($categories as $category) {
            DB::table('menu_categories')->insert([
                'title' => $category,
                'slug' => Str::slug($category),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
