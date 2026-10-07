<?php

namespace App\Services\Payment;

/**
 * Provider-agnostic view of a hosted checkout session.
 */
final readonly class GatewaySession
{
    public function __construct(
        public string $id,
        public ?string $url,
        public ?string $status,          // open | complete | expired
        public ?string $paymentStatus,   // paid | unpaid | no_payment_required
        public ?int $amountTotal,        // cents, after discounts
        public ?string $currency,
        public ?string $paymentIntentId,
        public ?int $orderId,
    ) {
    }

    public function isPaid(): bool
    {
        return $this->paymentStatus === 'paid';
    }
}
