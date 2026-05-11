<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function booted()
    {
        static::creating(function ($category) {
    
            $slug = Str::slug($category->name);
            $original = $slug;
            $count = 1;
    
            // 🔥 حل التكرار
            while (\App\Models\Category::where('slug', $slug)->exists()) {
                $slug = $original . '-' . $count++;
            }
    
            $category->slug = $slug;
        });
    }
    public function products(): HasMany
    {
        
        return $this->hasMany(Product::class);
    }
}
