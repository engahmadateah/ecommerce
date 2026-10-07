<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product;
use Illuminate\Support\Str;

class Category extends Model
{
    use \App\Models\Concerns\LogsActivity;
    use \App\Models\Concerns\HasTranslations;

    protected $fillable = ['name', 'slug'];

    private const CACHE_KEY = 'shop.categories';

    /** All categories, cached (they change rarely and show on every product list). */
    public static function cachedAll()
    {
        return \Illuminate\Support\Facades\Cache::remember(self::CACHE_KEY, 600, fn () => static::all());
    }

    protected static function booted()
    {
        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget(self::CACHE_KEY));

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
