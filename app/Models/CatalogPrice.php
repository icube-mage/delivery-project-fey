<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogPrice extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'upload_hash',
        'sku',
        'name',
        'rrp',
        'cbp',
        'user_id',
        'brand',
        'marketplace',
        'start_date'
    ];
    
    public function user()
    {
        $this->belongsTo(User::class);
    }
}
