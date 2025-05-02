<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TableStatus::class,
            TableSeeder::class,
            RoleSeeder::class,
            MenuCategorySeeder::class,
            IngredientSeeder::class,
            MenuSeeder::class,
            IngredientMenuSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
