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

        for ($i = 0; $i < 20; $i++) {
            DB::table('orders')->insert([
                'customer_name' => $faker->name,
                'table_id' => $faker->unique()->numberBetween(1, 20),
                'status' => $faker->numberBetween(1, 3),
                'total_price' => $faker->randomFloat(2, 10, 500),
                'notes' => $faker->words(10, true),
                'payment_method' => $faker->randomElement(['cash', 'credit', 'debit', 'qris']),
                'payment_status' => $faker->randomElement(['unpaid', 'paid', 'refunded', 'failed']),
                'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                'updated_at' => now()
            ]);
        }
    }
}
