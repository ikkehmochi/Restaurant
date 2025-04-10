<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['title' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'manager', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'waiter', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'cashier', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
