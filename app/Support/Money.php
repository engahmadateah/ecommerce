<?php

namespace App\Support;

/**
 * All money maths in the checkout flow is done in integer cents to avoid
 * floating point drift. Decimals only exist at the database/view boundary.
 */
final class Money
{
    public static function toCents(float|int|string|null $amount): int
    {
        return (int) round(((float) ($amount ?? 0)) * 100);
    }

    /** Decimal string ("19.90") suitable for DECIMAL columns and display. */
    public static function fromCents(int $cents): string
    {
        return number_format($cents / 100, 2, '.', '');
    }
}
