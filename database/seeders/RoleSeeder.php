<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        collect([
            [
                'name' => 'Super Admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Store Operations',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Key Account Manager',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ])->each(function ($mp) {
            DB::table('roles')->insert($mp);
        });
    }
}