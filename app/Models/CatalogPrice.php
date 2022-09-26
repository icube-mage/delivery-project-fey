<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogPrice extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'upload_hash',
        'sku',
        'product_name',
        'retail_price',
        'discount_price',
        'user_id',
        'brand',
        'marketplace',
        'start_date',
        'is_discount',
        'warehouse'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function startDate(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Carbon::parse($value)->format('j F, Y'),
            set: fn ($value) => $value ?? Carbon::now(),
        );
    }
}
