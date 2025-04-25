<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menuItems = [
            'Appetizers' => [
                ['Spring Rolls', 'Crispy vegetable spring rolls', 5.99, 50],
                ['Chicken Wings', 'Spicy buffalo wings', 8.99, 40],
                ['Garlic Bread', 'Toasted bread with garlic butter', 4.99, 30],
                ['Mozzarella Sticks', 'Breaded and fried cheese sticks', 6.99, 25],
                ['Soup of the Day', 'Fresh homemade soup', 5.99, 20],
                ['Bruschetta', 'Toasted bread with tomatoes and herbs', 6.99, 15],
                ['Calamari', 'Fried squid rings with dipping sauce', 9.99, 10],
                ['Nachos', 'Tortilla chips with cheese and toppings', 7.99, 50],
                ['Shrimp Cocktail', 'Chilled shrimp with cocktail sauce', 10.99, 12],
                ['Stuffed Mushrooms', 'Mushrooms filled with herbs and cheese', 7.99, 18],
            ],
            'Main Course' => [
                ['Grilled Steak', 'Prime cut beef steak', 24.99, 20],
                ['Salmon Fillet', 'Fresh salmon with herbs', 22.99, 15],
                ['Chicken Alfredo', 'Pasta in creamy sauce', 16.99, 25],
                ['Vegetable Curry', 'Mixed vegetables in curry sauce', 14.99, 30],
                ['BBQ Ribs', 'Tender pork ribs with BBQ sauce', 19.99, 10],
                ['Fish and Chips', 'Battered fish with french fries', 15.99, 20],
                ['Beef Burger', 'Juicy beef patty with toppings', 13.99, 50],
                ['Mushroom Risotto', 'Creamy rice with mushrooms', 15.99, 15],
                ['Lamb Chops', 'Grilled lamb with mint sauce', 26.99, 8],
                ['Shrimp Scampi', 'Garlic butter shrimp with pasta', 20.99, 12],
            ],
            'Desserts' => [
                ['Chocolate Cake', 'Rich chocolate layer cake', 6.99, 20],
                ['Cheesecake', 'New York style cheesecake', 7.99, 15],
                ['Apple Pie', 'Homemade apple pie', 5.99, 25],
                ['Ice Cream', 'Assorted flavors', 4.99, 50],
                ['Tiramisu', 'Classic Italian dessert', 7.99, 10],
                ['Crème Brûlée', 'French vanilla custard', 8.99, 8],
                ['Brownie Sundae', 'Warm brownie with ice cream', 7.99, 12],
                ['Fruit Tart', 'Fresh fruit with custard', 6.99, 15],
                ['Bread Pudding', 'Warm spiced bread pudding', 5.99, 20],
                ['Lemon Sorbet', 'Refreshing citrus sorbet', 4.99, 30],
            ],
            'Beverages' => [
                ['Coffee', 'Fresh brewed coffee', 2.99, 100],
                ['Iced Tea', 'House-made iced tea', 2.99, 80],
                ['Soda', 'Assorted soft drinks', 2.49, 200],
                ['Lemonade', 'Fresh squeezed lemonade', 3.99, 50],
                ['Smoothie', 'Fruit smoothie', 5.99, 30],
                ['Hot Tea', 'Assorted tea selection', 2.99, 60],
                ['Milkshake', 'Hand-spun milkshake', 5.99, 25],
                ['Juice', 'Fresh fruit juice', 3.99, 40],
                ['Sparkling Water', 'Mineral water', 2.99, 100],
                ['Hot Chocolate', 'Rich chocolate drink', 3.99, 30],
            ],
            'Special Items' => [
                ['Chef Special', 'Daily chef creation', 25.99, 5],
                ['Seafood Platter', 'Assorted seafood', 34.99, 8],
                ['Vegetarian Platter', 'Assorted vegetables', 18.99, 10],
                ['Party Package', 'Serves 4-6 people', 59.99, 3],
                ['Surf and Turf', 'Steak and lobster', 39.99, 6],
                ['Family Feast', 'Family-style dining', 49.99, 4],
                ['Tasting Menu', '5-course tasting', 45.99, 5],
                ['Weekend Special', 'Limited time offer', 29.99, 7],
                ['Holiday Menu', 'Seasonal special', 32.99, 8],
                ['Lunch Set', 'Complete lunch meal', 15.99, 20],
            ],
        ];

        foreach ($menuItems as $category => $items) {
            $categoryId = DB::table('menu_categories')
                ->where('title', $category)
                ->value('id');

            foreach ($items as [$name, $description, $price, $stock]) {
                Menu::factory()->create([
                    'name' => $name,
                    'description' => $description,
                    'category_id' => $categoryId,
                    'price' => $price,
                    'stock' => $stock,
                ]);
            }
        }
    }
}
