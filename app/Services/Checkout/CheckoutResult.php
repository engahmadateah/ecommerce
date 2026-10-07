<?php

namespace App\Services\Checkout;

use App\Models\Order;

final readonly class CheckoutResult
{
    /**
     * @param string|null $redirectUrl hosted payment page, or null when nothing
     *                                 has to be paid (order fully covered by a coupon)
     */
    public function __construct(
        public Order $order,
        public ?string $redirectUrl,
    ) {
    }
}
