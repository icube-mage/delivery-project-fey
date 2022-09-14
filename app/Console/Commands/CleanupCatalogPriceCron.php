<?php

namespace App\Console\Commands;

use App\Models\CatalogPrice;
use App\Models\Configuration;
use Illuminate\Console\Command;

class CleanupCatalogPriceCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catalogprice:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup Catalog Price records';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("Start cron cleaning up catalog price!");
        $config = Configuration::where('key', 'average_max_time_calculate')->first();
        CatalogPrice::whereDate( 'start_date', '<', now()->subDays($config->value))->delete();
    }
}
