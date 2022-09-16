<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogPriceAvg extends Model
{
    use HasFactory;
    protected $table = 'catalog_price_averages';

    protected $fillable = [
        'sku',
        'name',
        'rrp',
        'cbp',
        'user_id',
        'brand',
        'marketplace',
        'start_date'
    ];
}
