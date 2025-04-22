<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menuItems = [
            'Appetizers' => [
                ['Spring Rolls', 'Crispy vegetable spring rolls', 5.99],
                ['Chicken Wings', 'Spicy buffalo wings', 8.99],
                ['Garlic Bread', 'Toasted bread with garlic butter', 4.99],
                ['Mozzarella Sticks', 'Breaded and fried cheese sticks', 6.99],
                ['Soup of the Day', 'Fresh homemade soup', 5.99],
                ['Bruschetta', 'Toasted bread with tomatoes and herbs', 6.99],
                ['Calamari', 'Fried squid rings with dipping sauce', 9.99],
                ['Nachos', 'Tortilla chips with cheese and toppings', 7.99],
                ['Shrimp Cocktail', 'Chilled shrimp with cocktail sauce', 10.99],
                ['Stuffed Mushrooms', 'Mushrooms filled with herbs and cheese', 7.99],
            ],
            'Main Course' => [
                ['Grilled Steak', 'Prime cut beef steak', 24.99],
                ['Salmon Fillet', 'Fresh salmon with herbs', 22.99],
                ['Chicken Alfredo', 'Pasta in creamy sauce', 16.99],
                ['Vegetable Curry', 'Mixed vegetables in curry sauce', 14.99],
                ['BBQ Ribs', 'Tender pork ribs with BBQ sauce', 19.99],
                ['Fish and Chips', 'Battered fish with french fries', 15.99],
                ['Beef Burger', 'Juicy beef patty with toppings', 13.99],
                ['Mushroom Risotto', 'Creamy rice with mushrooms', 15.99],
                ['Lamb Chops', 'Grilled lamb with mint sauce', 26.99],
                ['Shrimp Scampi', 'Garlic butter shrimp with pasta', 20.99],
            ],
            'Desserts' => [
                ['Chocolate Cake', 'Rich chocolate layer cake', 6.99],
                ['Cheesecake', 'New York style cheesecake', 7.99],
                ['Apple Pie', 'Homemade apple pie', 5.99],
                ['Ice Cream', 'Assorted flavors', 4.99],
                ['Tiramisu', 'Classic Italian dessert', 7.99],
                ['Crème Brûlée', 'French vanilla custard', 8.99],
                ['Brownie Sundae', 'Warm brownie with ice cream', 7.99],
                ['Fruit Tart', 'Fresh fruit with custard', 6.99],
                ['Bread Pudding', 'Warm spiced bread pudding', 5.99],
                ['Lemon Sorbet', 'Refreshing citrus sorbet', 4.99],
            ],
            'Beverages' => [
                ['Coffee', 'Fresh brewed coffee', 2.99],
                ['Iced Tea', 'House-made iced tea', 2.99],
                ['Soda', 'Assorted soft drinks', 2.49],
                ['Lemonade', 'Fresh squeezed lemonade', 3.99],
                ['Smoothie', 'Fruit smoothie', 5.99],
                ['Hot Tea', 'Assorted tea selection', 2.99],
                ['Milkshake', 'Hand-spun milkshake', 5.99],
                ['Juice', 'Fresh fruit juice', 3.99],
                ['Sparkling Water', 'Mineral water', 2.99],
                ['Hot Chocolate', 'Rich chocolate drink', 3.99],
            ],
            'Special Items' => [
                ['Chef Special', 'Daily chef creation', 25.99],
                ['Seafood Platter', 'Assorted seafood', 34.99],
                ['Vegetarian Platter', 'Assorted vegetables', 18.99],
                ['Party Package', 'Serves 4-6 people', 59.99],
                ['Surf and Turf', 'Steak and lobster', 39.99],
                ['Family Feast', 'Family-style dining', 49.99],
                ['Tasting Menu', '5-course tasting', 45.99],
                ['Weekend Special', 'Limited time offer', 29.99],
                ['Holiday Menu', 'Seasonal special', 32.99],
                ['Lunch Set', 'Complete lunch meal', 15.99],
            ],
        ];

        foreach ($menuItems as $category => $items) {
            $categoryId = DB::table('menu_categories')
                ->where('title', $category)
                ->value('id');

            foreach ($items as [$name, $description, $price]) {
                DB::table('menus')->insert([
                    'name' => $name,
                    'description' => $description,
                    'category_id' => $categoryId,
                    'price' => $price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
