<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $capacities = [2, 4, 6, 8];

        for ($i = 1; $i <= 50; $i++) {
            DB::table('tables')->insert([
                'number' => 'T' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'capacity' => $capacities[array_rand($capacities)],
                'status_id' => rand(1, 3),
                'floor' => rand(1, 3),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
