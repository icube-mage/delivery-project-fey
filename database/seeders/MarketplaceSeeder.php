<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MarketplaceSeeder extends Seeder
{
    public function run()
    {
        collect([
            [
                'name' => 'Tokopedia',
                'slug' => 'tokopedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shopee',
                'slug' => 'shopee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lazada',
                'slug' => 'lazada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bukalapak',
                'slug' => 'bukalapak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Blibli',
                'slug' => 'blibli',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ])->each(function ($mp) {
            DB::table('marketplaces')->insert($mp);
        });
    }
}