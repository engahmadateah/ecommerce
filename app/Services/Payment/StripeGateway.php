<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Services\Checkout\CheckoutQuote;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\StripeObject;
use Stripe\Webhook;

final class StripeGateway implements PaymentGateway
{
    private const CURRENCY = 'usd';

    private ?StripeClient $client = null;

    public function __construct(
        private readonly string $secret,
        private readonly string $webhookSecret,
    ) {
    }

    public function createCheckoutSession(
        Order $order,
        CheckoutQuote $quote,
        string $customerEmail,
        string $successUrl,
        string $cancelUrl,
    ): GatewaySession {
        $params = [
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => array_map(fn ($line) => [
                'price_data' => [
                    'currency' => self::CURRENCY,
                    'product_data' => ['name' => $line->name],
                    'unit_amount' => $line->unitPriceCents,
                ],
                'quantity' => $line->quantity,
            ], $quote->lines),
            'customer_email' => $customerEmail,
            'client_reference_id' => (string) $order->id,
            'metadata' => ['order_id' => (string) $order->id],
            'payment_intent_data' => ['metadata' => ['order_id' => (string) $order->id]],
            'expires_at' => $order->expires_at->getTimestamp(),
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ];

        // Shipping and (added) tax are calculated server-side, like everything else.
        if ($quote->shippingCents > 0) {
            $params['shipping_options'] = [[
                'shipping_rate_data' => [
                    'type' => 'fixed_amount',
                    'fixed_amount' => ['amount' => $quote->shippingCents, 'currency' => self::CURRENCY],
                    'display_name' => 'Shipping',
                ],
            ]];
        }

        if ($quote->taxCents > 0) {
            $params['line_items'][] = [
                'price_data' => [
                    'currency' => self::CURRENCY,
                    'product_data' => ['name' => $quote->taxLabel],
                    'unit_amount' => $quote->taxCents,
                ],
                'quantity' => 1,
            ];
        }

        // Our discount is calculated server-side; Stripe only mirrors it so the
        // hosted page and the receipt show the same numbers we charge.
        if ($quote->discountCents > 0) {
            $coupon = $this->client()->coupons->create([
                'amount_off' => $quote->discountCents,
                'currency' => self::CURRENCY,
                'duration' => 'once',
                'max_redemptions' => 1,
                'name' => $quote->coupon?->code ?? 'Discount',
            ], ['idempotency_key' => "order-{$order->id}-coupon"]);

            $params['discounts'] = [['coupon' => $coupon->id]];
        }

        $session = $this->client()->checkout->sessions->create(
            $params,
            ['idempotency_key' => "order-{$order->id}-session"],
        );

        return $this->toSession($session);
    }

    public function retrieveSession(string $sessionId): GatewaySession
    {
        return $this->toSession($this->client()->checkout->sessions->retrieve($sessionId));
    }

    public function expireSession(string $sessionId): void
    {
        $this->client()->checkout->sessions->expire($sessionId);
    }

    public function refund(string $paymentIntentId, int $amountCents, string $idempotencyKey): string
    {
        $refund = $this->client()->refunds->create(
            ['payment_intent' => $paymentIntentId, 'amount' => $amountCents],
            ['idempotency_key' => $idempotencyKey],
        );

        return (string) $refund->id;
    }

    public function parseWebhook(string $payload, string $signatureHeader): GatewayEvent
    {
        try {
            $event = Webhook::constructEvent($payload, $signatureHeader, $this->webhookSecret);
        } catch (\UnexpectedValueException|SignatureVerificationException $e) {
            throw new InvalidWebhookException($e->getMessage(), 0, $e);
        }

        $session = str_starts_with($event->type, 'checkout.session.')
            ? $this->toSession($event->data->object)
            : null;

        return new GatewayEvent($event->type, $session);
    }

    private function client(): StripeClient
    {
        return $this->client ??= new StripeClient($this->secret);
    }

    private function toSession(StripeObject $s): GatewaySession
    {
        $metadata = $s->metadata ?? null;
        $orderId = $metadata['order_id'] ?? $s->client_reference_id ?? null;
        $intent = $s->payment_intent ?? null;

        return new GatewaySession(
            id: (string) $s->id,
            url: $s->url ?? null,
            status: $s->status ?? null,
            paymentStatus: $s->payment_status ?? null,
            amountTotal: isset($s->amount_total) ? (int) $s->amount_total : null,
            currency: isset($s->currency) ? strtolower((string) $s->currency) : null,
            paymentIntentId: is_object($intent) ? ($intent->id ?? null) : (is_string($intent) ? $intent : null),
            orderId: $orderId !== null ? (int) $orderId : null,
        );
    }
}
