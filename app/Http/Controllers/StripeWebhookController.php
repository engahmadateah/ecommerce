<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Checkout\OrderService;
use App\Services\Payment\GatewaySession;
use App\Services\Payment\InvalidWebhookException;
use App\Services\Payment\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Stripe is the source of truth for payments. Handling is idempotent, so
 * Stripe retrying or re-sending an event can never double-charge points,
 * coupons or stock. A non-2xx answer makes Stripe retry, which is what we
 * want if something failed on our side.
 */
class StripeWebhookController extends Controller
{
    public function __invoke(Request $request, PaymentGateway $gateway, OrderService $orders): Response
    {
        try {
            $event = $gateway->parseWebhook($request->getContent(), (string) $request->header('Stripe-Signature'));
        } catch (InvalidWebhookException) {
            return response('Invalid webhook', 400);
        }

        if ($event->session === null) {
            return response('Ignored', 200);
        }

        $order = $this->findOrder($event->session);

        if (! $order) {
            return response('Unknown order', 200); // not ours (or already purged): retrying won't help
        }

        match ($event->type) {
            'checkout.session.completed',
            'checkout.session.async_payment_succeeded' => $event->session->isPaid()
                ? $orders->confirmPayment($order, $event->session)
                : null,

            'checkout.session.expired',
            'checkout.session.async_payment_failed' => $orders->cancel($order),

            default => null,
        };

        return response('OK', 200);
    }

    private function findOrder(GatewaySession $session): ?Order
    {
        return Order::query()
            ->where('stripe_session_id', $session->id)
            ->when($session->orderId, fn ($q) => $q->orWhere('id', $session->orderId))
            ->first();
    }
}
