<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use \App\Models\Concerns\LogsActivity;

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

        'shipping_fee',
        'free_shipping_threshold',
        'tax_rate',
        'tax_included',
        'tax_label',
    ];

    protected function casts(): array
    {
        return ['tax_included' => 'boolean'];
    }

    private const CACHE_KEY = 'shop.settings';

    protected static function booted(): void
    {
        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget(self::CACHE_KEY));
    }

    /** The shop settings row, cached for pages that only display it (footer, policies...). */
    public static function current(): ?self
    {
        return \Illuminate\Support\Facades\Cache::remember(self::CACHE_KEY, 600, fn () => static::query()->first());
    }
}
