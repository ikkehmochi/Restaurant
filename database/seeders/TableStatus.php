<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TableStatus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('table_statuses')->insert([
            ['title' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'occupied', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'reserved', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
