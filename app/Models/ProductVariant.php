<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One sellable option of a product (size, colour, ...). Has its own stock and,
 * optionally, its own price. The parent product's `stock` column always holds
 * the sum of its active variants, so product lists keep working unchanged.
 */
class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'price',
        'discount_price',
        'stock',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::saved(fn (ProductVariant $v) => $v->product?->syncStockFromVariants());
        static::deleted(fn (ProductVariant $v) => $v->product?->syncStockFromVariants());
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where($query->qualifyColumn('is_active'), true);
    }

    /** The price charged right now for this option. */
    public function currentPrice(): float
    {
        if ($this->price === null) {
            return $this->product->currentPrice();
        }

        if ($this->discount_price !== null && (float) $this->discount_price < (float) $this->price) {
            return (float) $this->discount_price;
        }

        return (float) $this->price;
    }
}
