<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            DB::table('orders')->insert([
                'user_id' => $faker->numberBetween(1, 5),
                'table_id' => $faker->numberBetween(1, 20),
                'order_status_id' => $faker->numberBetween(1, 4),
                'total_price' => $faker->randomFloat(2, 10, 500),
                'payment_method' => $faker->randomElement(['cash', 'credit_card', 'debit_card']),
                'payment_status' => $faker->randomElement(['pending', 'completed', 'failed']),
                'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                'updated_at' => now()
            ]);
        }
    }
}
