<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Safety net for abandoned payments: releases reserved stock/coupons if a webhook never arrived.
Schedule::command('orders:expire-pending')->everyFiveMinutes();

// Nightly database copy (kept 14 days). Also copy storage/app/backups somewhere off the server!
Schedule::command('db:backup')->dailyAt('03:00');

// One e-mail a day listing products that are running out.
Schedule::command('stock:alert')->dailyAt('09:00');

// One reminder e-mail for carts left behind (see config/shop.php abandoned_cart).
Schedule::command('carts:remind')->hourly();
