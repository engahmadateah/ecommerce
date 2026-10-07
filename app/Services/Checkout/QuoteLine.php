<?php

namespace App\Services\Checkout;

final readonly class QuoteLine
{
    public function __construct(
        public string $cartKey,
        public string $name,
        public int $unitPriceCents,
        public int $quantity,
        public ?int $productId = null,
        public ?int $packageId = null,
        public ?int $variantId = null,
    ) {
    }

    public function totalCents(): int
    {
        return $this->unitPriceCents * $this->quantity;
    }
}
