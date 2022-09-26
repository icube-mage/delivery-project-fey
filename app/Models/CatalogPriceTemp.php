<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogPriceTemp extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sku',
        'product_name',
        'retail_price',
        'discount_price',
        'user_id',
        'brand',
        'marketplace',
        'start_date',
        'is_whitelist',
        'is_discount',
        'warehouse'
    ];
}
