<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Services\Checkout\CheckoutQuote;

interface PaymentGateway
{
    public function createCheckoutSession(
        Order $order,
        CheckoutQuote $quote,
        string $customerEmail,
        string $successUrl,
        string $cancelUrl,
    ): GatewaySession;

    public function retrieveSession(string $sessionId): GatewaySession;

    /**
     * Gives money back to the card used for a payment. The idempotency key makes
     * repeated calls refund only once. Returns the gateway's refund id.
     */
    public function refund(string $paymentIntentId, int $amountCents, string $idempotencyKey): string;

    /** Stops a still-open session so it can no longer be paid. */
    public function expireSession(string $sessionId): void;

    /**
     * Verifies the signature and parses a webhook.
     *
     * @throws InvalidWebhookException
     */
    public function parseWebhook(string $payload, string $signatureHeader): GatewayEvent;
}
