<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Applies the visitor's language and currency (saved in the session) to every request. */
class SetLocaleAndCurrency
{
    public function handle(Request $request, Closure $next): Response
    {
        $locales = array_keys(config('shop.locales'));

        $locale = session('locale');

        if (! in_array($locale, $locales, true)) {
            // First visit: follow the browser language when we support it.
            $locale = $request->getPreferredLanguage($locales) ?: config('app.locale');
        }

        app()->setLocale(in_array($locale, $locales, true) ? $locale : config('app.fallback_locale'));

        return $next($request);
    }
}
