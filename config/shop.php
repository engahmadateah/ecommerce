<?php

/*
| Languages and display currencies of the shop.
|
| Prices are stored and CHARGED in USD (Stripe). Other currencies are shown as
| an estimate using the rates below: update them from time to time (or set
| SHOP_RATE_EUR / SHOP_RATE_SAR in .env). Rate = how many units of that
| currency equal 1 USD.
*/

return [

    // The language the product names/descriptions in the database are written in.
    'base_locale' => 'en',

    'locales' => [
        'en' => 'English',
        'ar' => 'العربية',
    ],

    // Languages written right-to-left.
    'rtl' => ['ar'],

    'default_currency' => 'USD',

    /*
    | Shipping and tax defaults (USD). The admin "Settings" page overrides these.
    |  - fee:        flat shipping price per order (0 = no shipping charge)
    |  - free_over:  orders whose goods total reaches this amount ship free (null = never)
    |  - tax rate:   percent. 0 disables tax. "included" = prices already contain it
    |                (shown as "includes VAT"); false = tax is added at checkout.
    */
    'shipping' => [
        'fee' => (float) env('SHOP_SHIPPING_FEE', 0),
        'free_over' => env('SHOP_FREE_SHIPPING_OVER') !== null ? (float) env('SHOP_FREE_SHIPPING_OVER') : null,
    ],

    'tax' => [
        'rate' => (float) env('SHOP_TAX_RATE', 0),
        'included' => (bool) env('SHOP_TAX_INCLUDED', true),
        'label' => env('SHOP_TAX_LABEL', 'VAT'),
    ],

    // Customers can ask to return an order for this many days after paying.
    // Reminder e-mail for logged-in customers who left items in the cart.
    'abandoned_cart' => [
        'delay_hours' => (int) env('SHOP_CART_REMINDER_HOURS', 3),
        'max_days' => (int) env('SHOP_CART_REMINDER_MAX_DAYS', 7),
    ],

    // Product photos are saved as WebP (needs PHP gd with WebP; otherwise originals are kept).
    'images' => [
        'webp' => (bool) env('SHOP_IMAGES_WEBP', true),
        'max_width' => (int) env('SHOP_IMAGE_MAX_WIDTH', 1600),
        'quality' => (int) env('SHOP_IMAGE_QUALITY', 82),
    ],

    'returns' => [
        'days' => (int) env('SHOP_RETURN_DAYS', 14),
    ],

    // Products at or below this stock level show up in the admin "Low stock" list and in the daily alert e-mail.
    'low_stock_threshold' => (int) env('SHOP_LOW_STOCK', 5),

    'currencies' => [
        'USD' => ['rate' => 1.0, 'symbol' => '$', 'symbol_ar' => '$', 'name' => 'US Dollar'],
        'EUR' => ['rate' => (float) env('SHOP_RATE_EUR', 0.92), 'symbol' => '€', 'symbol_ar' => '€', 'name' => 'Euro'],
        'SAR' => ['rate' => (float) env('SHOP_RATE_SAR', 3.75), 'symbol' => 'SAR ', 'symbol_ar' => 'ر.س ', 'name' => 'Saudi Riyal'],
    ],

];
