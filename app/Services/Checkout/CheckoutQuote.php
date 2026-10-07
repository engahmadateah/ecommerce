<?php

namespace App\Services\Checkout;

use App\Models\Coupon;

/**
 * The single source of truth for "what does this cart cost right now".
 * Everything is in integer cents.
 */
final readonly class CheckoutQuote
{
    /** @param list<QuoteLine> $lines */
    public function __construct(
        public array $lines,
        public int $subtotalCents,
        public int $discountCents,
        public int $totalCents,
        public ?Coupon $coupon = null,
        public int $shippingCents = 0,
        public int $taxCents = 0,              // tax ADDED on top (prices exclude tax)
        public int $taxIncludedCents = 0,      // tax already INSIDE the total (informational)
        public string $taxLabel = 'VAT',
        public ?int $freeShippingRemainingCents = null,
    ) {
    }
}
