<?php

namespace App\Services\Checkout;

use App\Enums\OrderStatus;
use App\Exceptions\CheckoutException;
use App\Models\Order;
use App\Models\User;
use App\Services\CartService;
use App\Services\Payment\PaymentGateway;
use Throwable;

/**
 * Orchestrates "cart ➜ pending order ➜ hosted payment page".
 * Controllers only translate HTTP in and out of this class.
 */
class CheckoutService
{
    /** Stripe requires sessions to live at least 30 minutes. */
    private const PAYMENT_WINDOW_MINUTES = 31;

    public function __construct(
        private readonly CartService $cart,
        private readonly CouponService $coupons,
        private readonly PricingService $pricing,
        private readonly OrderService $orders,
        private readonly PaymentGateway $gateway,
    ) {
    }

    /**
     * @param array{full_name:string, phone:string, address:string, city:string, country:string} $shipping
     * @throws CheckoutException
     */
    public function start(?User $user, array $shipping, ?int $couponId, ?string $guestEmail = null): CheckoutResult
    {
        $email = $user?->email ?? $guestEmail;

        if (! $email) {
            throw CheckoutException::emailRequired();
        }

        if ($this->cart->raw() === []) {
            throw CheckoutException::emptyCart();
        }

        // The customer must pay what they saw: if prices/stock moved, stop and let them review.
        if ($this->cart->isStale()) {
            $this->cart->getCart(); // persist the corrected cart so the cart page shows it
            throw CheckoutException::cartChanged();
        }

        $cart = $this->cart->getCart();
        $coupon = $this->coupons->resolve($couponId, $user);
        $quote = $this->pricing->quote($cart, $coupon);

        $this->abandonPreviousAttempts($user, $guestOrderIds = (array) session('guest_order_ids', []));

        $expiresAt = now()->addMinutes(self::PAYMENT_WINDOW_MINUTES);
        $order = $this->orders->createPending($user, $quote, $shipping, $expiresAt, $guestEmail);

        if (! $user) {
            // Lets this browser session see its own payment result (no account to prove it).
            session(['guest_order_ids' => [...$guestOrderIds, $order->id]]);
        }

        // Nothing to pay (coupon covers everything): confirm without a gateway.
        if ($quote->totalCents === 0) {
            $this->orders->confirmPayment($order, null);

            return new CheckoutResult($order, null);
        }

        try {
            $session = $this->gateway->createCheckoutSession(
                $order,
                $quote,
                $email,
                route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                route('checkout.cancel', $order),
            );

            $order->forceFill(['stripe_session_id' => $session->id])->save();
        } catch (Throwable $e) {
            report($e);
            $this->orders->cancel($order); // gives the reserved stock and coupon use back

            throw CheckoutException::payment();
        }

        return new CheckoutResult($order, $session->url);
    }

    /** Called when the customer comes back from the payment page. */
    public function reconcile(Order $order): Order
    {
        $this->orders->reconcile($order);

        return $order->refresh();
    }

    /** Called when the customer cancels on the payment page. */
    public function abandon(Order $order): bool
    {
        return $this->orders->abandon($order);
    }

    /** A customer retrying checkout shouldn't keep stock locked in an old attempt. */
    private function abandonPreviousAttempts(?User $user, array $guestOrderIds): void
    {
        Order::query()
            ->when(
                $user,
                fn ($q) => $q->where('user_id', $user->id),
                fn ($q) => $q->whereNull('user_id')->whereIn('id', $guestOrderIds ?: [0]),
            )
            ->where('status', OrderStatus::Pending->value)
            ->each(function (Order $order): void {
                $this->orders->abandon($order);
            });
    }
}
