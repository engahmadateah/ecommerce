<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public function locale(string $locale): RedirectResponse
    {
        abort_unless(array_key_exists($locale, config('shop.locales')), 404);

        session(['locale' => $locale]);

        return back();
    }

    public function currency(string $code): RedirectResponse
    {
        $code = strtoupper($code);

        abort_unless(array_key_exists($code, config('shop.currencies')), 404);

        session(['currency' => $code]);

        return back();
    }
}
