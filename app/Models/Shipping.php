<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = [
        'order_id',
        'full_name',
        'phone',
        'address',
        'city',
        'country',
        'carrier',
        'tracking_number',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}