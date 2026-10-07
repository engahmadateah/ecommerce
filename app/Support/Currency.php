<?php

namespace App\Support;

/** Shows USD amounts in the currency the visitor picked (display only). */
class Currency
{
    public static function code(): string
    {
        $code = session('currency', config('shop.default_currency'));

        return array_key_exists($code, config('shop.currencies')) ? $code : config('shop.default_currency');
    }

    public static function isConverted(): bool
    {
        return self::code() !== 'USD';
    }

    /** @param float|int|string|null $usd */
    public static function format($usd): string
    {
        $currency = config('shop.currencies.'.self::code());
        $amount = (float) $usd * $currency['rate'];
        $symbol = app()->getLocale() === 'ar' ? $currency['symbol_ar'] : $currency['symbol'];

        return $symbol.number_format($amount, 2);
    }
}
