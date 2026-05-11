<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'discount_price',
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}
public function wishedBy()
{
    return $this->belongsToMany(User::class, 'wishlists');
}

public function reviews()
{
    return $this->hasMany(Review::class);
}

public function packages()
{
    return $this->belongsToMany(Package::class);
}
public function getFinalPriceAttribute()
{
    return $this->discount_price ?? $this->price;
}

public function getDiscountPercentAttribute()
{
    if (!$this->discount_price) return null;

    return round(100 - ($this->discount_price / $this->price * 100));
}
}
