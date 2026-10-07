<?php

namespace App\Services\Payment;

final readonly class GatewayEvent
{
    public function __construct(
        public string $type,
        public ?GatewaySession $session,
    ) {
    }
}
