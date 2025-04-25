<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            ['Chicken', 'Fresh chicken meat', 100],
            ['Beef', 'Premium beef cuts', 80],
            ['Rice', 'Long grain rice', 150],
            ['Pasta', 'Italian pasta', 100],
            ['Tomatoes', 'Fresh tomatoes', 50],
            ['Cheese', 'Mozzarella cheese', 60],
            ['Lettuce', 'Fresh lettuce', 40],
            ['Potatoes', 'Fresh potatoes', 100],
            ['Fish', 'Fresh fish fillet', 50],
            ['Garlic', 'Fresh garlic', 30],
            ['Onion', 'Fresh onions', 50],
            ['Flour', 'All-purpose flour', 100],
            ['Eggs', 'Fresh eggs', 120],
            ['Milk', 'Fresh milk', 80],
            ['Butter', 'Dairy butter', 50]
        ];

        foreach ($ingredients as [$name, $description, $stock]) {
            Ingredient::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
                'stock' => $stock,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create additional random ingredients
        Ingredient::factory()->count(10)->create();
    }
}
