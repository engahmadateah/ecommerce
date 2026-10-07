<?php

namespace App\Services\Checkout;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use App\Services\Payment\GatewaySession;
use App\Services\Notifications\OrderNotifier;
use App\Services\Payment\PaymentGateway;
use App\Support\Money;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Owns the order life-cycle:
 *
 *   createPending ─▶ confirmPayment (paid)      idempotent, verified against the gateway
 *                └─▶ cancel (cancelled)         idempotent, gives stock + coupon use back
 *
 * Every transition takes a row lock and re-reads the status, so webhooks that
 * are replayed, arrive out of order, or race the /success page are harmless.
 */
class OrderService
{
    public function __construct(
        private readonly StockService $stock,
        private readonly CouponService $coupons,
        private readonly LoyaltyService $loyalty,
        private readonly PaymentGateway $gateway,
        private readonly OrderNotifier $notifier,
    ) {
    }

    /**
     * Creates the order in "pending", reserving stock and the coupon use in the
     * same transaction. Either everything is reserved or nothing is.
     *
     * @param array{full_name:string, phone:string, address:string, city:string, country:string} $shipping
     */
    public function createPending(?User $user, CheckoutQuote $quote, array $shipping, CarbonInterface $expiresAt, ?string $guestEmail = null): Order
    {
        return DB::transaction(function () use ($user, $quote, $shipping, $expiresAt, $guestEmail) {
            $this->stock->reserve($quote->lines);

            if ($quote->coupon) {
                $this->coupons->redeem($quote->coupon);
            }

            $order = Order::create([
                'user_id' => $user?->id,
                'guest_email' => $user ? null : $guestEmail,
                'status' => OrderStatus::Pending->value,
                'subtotal' => Money::fromCents($quote->subtotalCents),
                'discount_total' => Money::fromCents($quote->discountCents),
                'total_price' => Money::fromCents($quote->totalCents),
                'shipping_total' => Money::fromCents($quote->shippingCents),
                'tax_total' => Money::fromCents($quote->taxCents + $quote->taxIncludedCents),
                'tax_included' => $quote->taxIncludedCents > 0,
                'coupon_id' => $quote->coupon?->id,
                'expires_at' => $expiresAt,
            ]);

            foreach ($quote->lines as $line) {
                $order->items()->create([
                    'product_id' => $line->productId,
                    'package_id' => $line->packageId,
                    'variant_id' => $line->variantId,
                    'name' => $line->name,
                    'quantity' => $line->quantity,
                    'price' => Money::fromCents($line->unitPriceCents),
                ]);
            }

            $order->shipping()->create($shipping + ['status' => 'pending']);

            return $order;
        });
    }

    /**
     * Marks a pending order as paid. Safe to call any number of times.
     *
     * @param GatewaySession|null $session null only for orders with nothing to pay
     */
    public function confirmPayment(Order $order, ?GatewaySession $session): bool
    {
        $newlyPaid = false;

        $confirmed = DB::transaction(function () use ($order, $session, &$newlyPaid) {
            $locked = Order::query()->whereKey($order->id)->lockForUpdate()->first();

            if (! $locked) {
                return false;
            }

            if ($locked->paid_at !== null) {
                return true; // already confirmed (webhook replay / success page race)
            }

            if ($locked->status !== OrderStatus::Pending->value) {
                // e.g. cancelled by the expiry job right before the money arrived.
                Log::critical('Payment received for an order that is not pending', [
                    'order_id' => $locked->id,
                    'status' => $locked->status,
                    'session' => $session?->id,
                ]);

                return false;
            }

            $totalCents = Money::toCents($locked->total_price);

            if ($session !== null && ! $this->sessionMatches($locked, $session, $totalCents)) {
                return false;
            }

            $locked->forceFill([
                'status' => OrderStatus::Paid->value,
                'paid_at' => now(),
                'payment_intent_id' => $session?->paymentIntentId,
                'expires_at' => null,
            ])->save();

            $user = $locked->user_id
                ? User::query()->whereKey($locked->user_id)->lockForUpdate()->first()
                : null; // guests earn no loyalty points

            if ($user) {
                $this->loyalty->award($user, $totalCents);
            }

            $newlyPaid = true;

            return true;
        });

        $order->refresh();

        // Only on the first confirmation, so webhook replays never re-send e-mails.
        if ($newlyPaid) {
            $this->notifier->paid($order);
        }

        return $confirmed;
    }

    /** Cancels a pending order and gives stock and coupon use back. Idempotent. */
    public function cancel(Order $order): bool
    {
        $cancelled = DB::transaction(function () use ($order) {
            $locked = Order::query()->whereKey($order->id)->lockForUpdate()->first();

            if (! $locked || $locked->status !== OrderStatus::Pending->value) {
                return false;
            }

            $this->stock->release($locked);

            if ($locked->coupon_id) {
                $this->coupons->release($locked->coupon_id);
            }

            $locked->forceFill([
                'status' => OrderStatus::Cancelled->value,
                'expires_at' => null,
            ])->save();

            return true;
        });

        $order->refresh();

        return $cancelled;
    }

    /**
     * Stops the customer from paying and then cancels. Expiring the gateway
     * session first guarantees a late payment can't land on a cancelled order.
     * Returns true when the order ended up cancelled.
     */
    public function abandon(Order $order): bool
    {
        if ($order->stripe_session_id) {
            try {
                $this->gateway->expireSession($order->stripe_session_id);
            } catch (Throwable $e) {
                // Already paid/expired on the gateway side: trust its state instead.
                report($e);
                $this->reconcile($order);

                return $order->status === OrderStatus::Cancelled->value;
            }
        }

        $this->cancel($order);

        return $order->status === OrderStatus::Cancelled->value;
    }

    /**
     * Brings a pending order in line with what the gateway says. Used by the
     * /success page and the scheduled safety-net command.
     */
    public function reconcile(Order $order): void
    {
        if ($order->status !== OrderStatus::Pending->value) {
            return;
        }

        if (! $order->stripe_session_id) {
            if ($order->expires_at?->isPast()) {
                $this->cancel($order);
            }

            return;
        }

        try {
            $session = $this->gateway->retrieveSession($order->stripe_session_id);
        } catch (Throwable $e) {
            report($e);

            return;
        }

        if ($session->isPaid()) {
            $this->confirmPayment($order, $session);
        } elseif ($session->status === 'expired') {
            $this->cancel($order);
        } elseif ($session->status === 'open' && $order->expires_at?->isPast()) {
            try {
                $this->gateway->expireSession($order->stripe_session_id);
                $this->cancel($order);
            } catch (Throwable $e) {
                report($e); // probably paid in the meantime; the webhook will settle it
            }
        }
    }

    private function sessionMatches(Order $order, GatewaySession $session, int $totalCents): bool
    {
        $matches = $session->isPaid()
            && $session->id === $order->stripe_session_id
            && $session->amountTotal === $totalCents
            && $session->currency === 'usd';

        if (! $matches) {
            Log::critical('Gateway session does not match the order it claims to pay', [
                'order_id' => $order->id,
                'expected_cents' => $totalCents,
                'session_id' => $session->id,
                'session_paid' => $session->isPaid(),
                'session_amount' => $session->amountTotal,
                'session_currency' => $session->currency,
            ]);
        }

        return $matches;
    }
}
