<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;
use Illuminate\Support\Str;

class Product extends Model
{
    use \App\Models\Concerns\LogsActivity;
    use \App\Models\Concerns\HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'is_published',
        'gallery',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'discount_price',
    ];
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'gallery' => 'array',
        ];
    }

    protected static function booted(): void
    {
        // Every product gets a unique slug (used in its public URL).
        // New uploads are converted to smaller WebP files (see App\Support\ImageOptimizer).
        static::saving(function (Product $product) {
            if ($product->isDirty('image')) {
                $product->image = \App\Support\ImageOptimizer::optimize($product->image);
            }

            if ($product->isDirty('gallery') && is_array($product->gallery)) {
                $product->gallery = array_map(fn ($path) => \App\Support\ImageOptimizer::optimize($path), $product->gallery);
            }
        });

        static::creating(function (Product $product) {
            if (blank($product->slug)) {
                $product->slug = static::uniqueSlug((string) $product->name);
            }
        });
    }

    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $i = 2;

        while (static::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    /** Public URLs use the slug: /products/blue-shirt */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Old links with the numeric id keep working. */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field) {
            return parent::resolveRouteBinding($value, $field);
        }

        return $this->newQuery()->where('slug', $value)->first()
            ?? (ctype_digit((string) $value) ? $this->newQuery()->whereKey($value)->first() : null);
    }

    /** Products visible in the shop (not drafts). */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where($query->qualifyColumn('is_published'), true);
    }

    /** Main image first, then the extra gallery images. */
    public function getAllImagesAttribute(): array
    {
        return array_values(array_filter(array_merge([$this->image], $this->gallery ?? [])));
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    /** With variants, `stock` is the sum of the active ones (kept up to date automatically). */
    public function syncStockFromVariants(): void
    {
        if (! $this->variants()->exists()) {
            return;
        }

        $total = (int) $this->variants()->where('is_active', true)->sum('stock');

        static::query()->whereKey($this->id)->update(['stock' => $total]);
        $this->stock = $total;
    }

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

    /**
     * The price a customer is charged right now: the discount price when it is
     * a real discount, otherwise the regular price. Single rule for cart and checkout.
     */
    public function currentPrice(): float
    {
        if ($this->discount_price !== null && (float) $this->discount_price < (float) $this->price) {
            return (float) $this->discount_price;
        }

        return (float) $this->price;
    }

    /** In-stock products that other customers bought together with the given products. */
    public function scopeBoughtTogetherWith(Builder $query, iterable $productIds): Builder
    {
        $ids = collect($productIds)->filter()->map(fn ($id) => (int) $id)->values()->all();

        if ($ids === []) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->published()
            ->where('stock', '>', 0)
            ->whereIn('id', function ($sub) use ($ids) {
                $sub->select('oi2.product_id')
                    ->from('order_items as oi1')
                    ->join('order_items as oi2', 'oi1.order_id', '=', 'oi2.order_id')
                    ->whereIn('oi1.product_id', $ids)
                    ->whereNotIn('oi2.product_id', $ids)
                    ->whereNotNull('oi2.product_id');
            });
    }
}
