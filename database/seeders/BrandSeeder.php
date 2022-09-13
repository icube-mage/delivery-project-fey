<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run()
    {
        collect([
            [
                'name' => 'L\'OREAL PARIS',
                'slug' => 'loreal-paris',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GARNIER',
                'slug' => 'garnier',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GARNIER MEN',
                'slug' => 'garnier-men',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ])->each(function ($brand) {
            DB::table('brands')->insert($brand);
        });
    }
}