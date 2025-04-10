<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_status')->insert([
            ['title' => 'pending', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'preparing', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'served', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'paid', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'cancelled', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
