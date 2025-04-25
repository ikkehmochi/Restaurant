<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 0; $i < 50; $i++) {
            DB::table('order_details')->insert([
                'order_id' => $faker->numberBetween(1, 10),
                'menu_id' => $faker->numberBetween(1, 50),
                'quantity' => $faker->numberBetween(1, 5),
                'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                'updated_at' => now()
            ]);
        }
    }
}
