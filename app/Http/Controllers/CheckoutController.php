<?php

namespace App\Http\Controllers;

use App\Exceptions\CheckoutException;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\Checkout\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CheckoutService $checkout,
        private readonly CartService $cart,
    ) {
    }

    public function form()
    {
        return view('checkout.form');
    }

    /** Creates a pending order and sends the customer to the payment page. */
    public function checkout(CheckoutRequest $request): RedirectResponse
    {
        try {
            $result = $this->checkout->start(
                $request->user(),
                $request->shipping(),
                session('coupon_id'),
                $request->input('email'),
            );
        } catch (CheckoutException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        if ($result->redirectUrl === null) {
            $this->finishShopping();

            return $this->afterPurchase($request, $result->order);
        }

        return redirect()->away($result->redirectUrl);
    }

    /**
     * Where Stripe sends the customer after paying. This page never trusts the
     * URL: it asks the gateway what really happened, through the same idempotent
     * path the webhook uses.
     */
    public function success(Request $request): RedirectResponse|View
    {
        $sessionId = (string) $request->query('session_id', '');

        if ($sessionId === '') {
            return redirect('/');
        }

        $order = Order::query()
            ->where('stripe_session_id', $sessionId)
            ->firstOrFail();

        abort_unless($this->owns($request, $order), 404);

        $order = $this->checkout->reconcile($order);

        if ($order->paid_at !== null) {
            $this->finishShopping();

            return $this->afterPurchase($request, $order);
        }

        if ($order->status === 'cancelled') {
            return redirect('/cart')->with('error', 'The payment was not completed.');
        }

        return $request->user()
            ? redirect('/orders')->with('success', 'Payment received — we are confirming your order.')
            : view('success', ['order' => $order, 'confirming' => true]);
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        abort_unless($this->owns($request, $order), 404);

        if (! $this->checkout->abandon($order)) {
            return redirect('/orders'); // it was paid in the meantime
        }

        return redirect('/cart')->with('error', 'Payment cancelled. Your cart is still here.');
    }

    /** Customers go to their orders page; guests (no account) see a thank-you page. */
    private function afterPurchase(Request $request, Order $order): RedirectResponse|View
    {
        if ($request->user()) {
            return redirect('/orders')->with('success', 'Order placed successfully');
        }

        return view('success', ['order' => $order, 'confirming' => false]);
    }

    private function owns(Request $request, Order $order): bool
    {
        return $order->isOwnedBy($request->user(), (array) session('guest_order_ids', []));
    }

    private function finishShopping(): void
    {
        $this->cart->clear();
        session()->forget('coupon_id');
    }
}
