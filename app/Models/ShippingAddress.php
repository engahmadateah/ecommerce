<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class shippingaddress extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'country',
        'city',
        'address_line',
        'postal_code',
    ];
}
