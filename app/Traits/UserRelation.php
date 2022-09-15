<?php

namespace App\Traits;

use App\Models\CatalogPrice;
use App\Models\CatalogPriceAvg;
use App\Models\CatalogPriceTemp;
use App\Models\HistoryUser;

trait UserRelation
{
    public function catalogPrices()
    {
        return $this->hasMany(CatalogPrice::class);
    }

    public function catalogPriceAverages()
    {
        return $this->hasMany(CatalogPriceAvg::class);
    }

    public function catalogPriceTemporary()
    {
        return $this->hasMany(CatalogPriceTemp::class);
    }

    public function uploadHistory()
    {
        return $this->hasMany(HistoryUser::class);
    }
}