<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'brand',
        'marketplace',
        'total_records',
        'false_price',
        'extras'
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
