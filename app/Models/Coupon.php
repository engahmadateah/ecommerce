<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'title',
        'description',
        'how_to_use',
        'image',
        'type',
        'value',
        'expires_at',
        'usage_limit',
        'used',
        'required_level',
        'level',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}