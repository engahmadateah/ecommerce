<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [

        'site_name',

        'facebook',
        'instagram',
        'twitter',
        'tiktok',

        'email',
        'phone',

        'address',

        'privacy_policy',
        'terms',
        'about',

        'footer_text',
    ];
}