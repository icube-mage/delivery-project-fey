<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    public function run()
    {
        collect([
            [
                'key' => 'csv_field_separator',
                'value' => ',',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'average_max_time_calculate',
                'value' => '90',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'crontab_schedule_running',
                'value' => '30 0 * * *',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'logo_image_placeholder',
                'value' => 'assets/images/fairytail.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ])->each(function ($conf) {
            DB::table('configurations')->insert($conf);
        });
    }
}