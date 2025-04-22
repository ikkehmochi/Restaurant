<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_statuses')->insert([
            ['title' => 'pending', 'slug' => 'pending', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'preparing', 'slug' => 'preparing', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'served', 'slug' => 'served', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'paid', 'slug' => 'paid', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'cancelled', 'slug' => 'cancelled', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
