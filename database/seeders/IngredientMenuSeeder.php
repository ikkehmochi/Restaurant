<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Ingredient;

class IngredientMenuSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = Ingredient::pluck('id')->toArray();

        Menu::all()->each(function ($menu) use ($ingredients) {
            $ingredientSet = collect($ingredients)->random(rand(2, 5));

            $pivotData = [];
            foreach ($ingredientSet as $ingredientId) {
                $pivotData[$ingredientId] = [
                    'quantity' => rand(1, 10), // quantity in grams/ml/etc.
                ];
            }

            $menu->ingredients()->sync($pivotData);
        });
    }
}
