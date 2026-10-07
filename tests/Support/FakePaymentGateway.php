<?php

namespace Tests\Support;

use App\Models\Order;
use App\Services\Checkout\CheckoutQuote;
use App\Services\Payment\GatewayEvent;
use App\Services\Payment\GatewaySession;
use App\Services\Payment\InvalidWebhookException;
use App\Services\Payment\PaymentGateway;
use RuntimeException;

/**
 * In-memory stand-in for Stripe so the whole checkout can be tested without network access.
 * Webhook payloads in tests are JSON like {"type": "...", "session_id": "cs_test_1"};
 * the only valid signature is the string "valid".
 */
class FakePaymentGateway implements PaymentGateway
{
    /** @var array<string, GatewaySession> */
    public array $sessions = [];

    /** @var list<string> */
    public array $expired = [];

    public bool $failCreate = false;

    public function createCheckoutSession(
        Order $order,
        CheckoutQuote $quote,
        string $customerEmail,
        string $successUrl,
        string $cancelUrl,
    ): GatewaySession {
        if ($this->failCreate) {
            throw new RuntimeException('Stripe is down');
        }

        return $this->sessions['cs_test_' . $order->id] = new GatewaySession(
            id: 'cs_test_' . $order->id,
            url: 'https://checkout.stripe.test/' . $order->id,
            status: 'open',
            paymentStatus: 'unpaid',
            amountTotal: $quote->totalCents,
            currency: 'usd',
            paymentIntentId: null,
            orderId: $order->id,
        );
    }

    public function retrieveSession(string $sessionId): GatewaySession
    {
        return $this->sessions[$sessionId] ?? throw new RuntimeException("No such session {$sessionId}");
    }

    public function expireSession(string $sessionId): void
    {
        if ($this->retrieveSession($sessionId)->status !== 'open') {
            throw new RuntimeException('Only open sessions can be expired');
        }

        $this->update($sessionId, ['status' => 'expired']);
        $this->expired[] = $sessionId;
    }

    /** @var list<array{intent:string,amount:int,key:string}> */
    public array $refunds = [];

    public bool $failRefund = false;

    public function refund(string $paymentIntentId, int $amountCents, string $idempotencyKey): string
    {
        if ($this->failRefund) {
            throw new RuntimeException('Refund failed');
        }

        $this->refunds[] = ['intent' => $paymentIntentId, 'amount' => $amountCents, 'key' => $idempotencyKey];

        return 're_test_' . count($this->refunds);
    }

    public function parseWebhook(string $payload, string $signatureHeader): GatewayEvent
    {
        if ($signatureHeader !== 'valid') {
            throw new InvalidWebhookException('Bad signature');
        }

        $data = json_decode($payload, true, flags: JSON_THROW_ON_ERROR);

        return new GatewayEvent($data['type'], $this->sessions[$data['session_id']] ?? null);
    }

    /** Simulates the customer paying on the hosted page. */
    public function pay(string $sessionId): void
    {
        $this->update($sessionId, [
            'status' => 'complete',
            'paymentStatus' => 'paid',
            'paymentIntentId' => 'pi_' . $sessionId,
        ]);
    }

    public function update(string $sessionId, array $changes): void
    {
        $current = $this->retrieveSession($sessionId);

        $this->sessions[$sessionId] = new GatewaySession(...array_merge(get_object_vars($current), $changes));
    }
}
