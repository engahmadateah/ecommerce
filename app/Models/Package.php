<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['name', 'description', 'price'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
