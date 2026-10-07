<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use App\Services\Returns\ReturnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Order tracking and invoices. Guests have no account, so each order has a
 * secret link (public_token) that is e-mailed to the customer.
 */
class OrderTrackingController extends Controller
{
    public function form(): View
    {
        return view('orders.track-form');
    }

    /** "Where is my order?" — order number + the e-mail used at checkout. */
    public function lookup(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'order' => ['required', 'integer'],
            'email' => ['required', 'email'],
        ]);

        $order = Order::query()->with('user')->find($data['order']);

        // Same answer whether the order exists or not: no way to probe for orders.
        if (! $order || strcasecmp((string) $order->customerEmail(), $data['email']) !== 0) {
            return back()->withInput()->withErrors(['order' => __('We could not find an order with these details.')]);
        }

        return redirect($order->trackingUrl());
    }

    public function show(string $token, ReturnService $returns): View
    {
        $order = $this->byToken($token);
        $order->load(['items', 'shipping', 'returns']);

        return view('orders.track', [
            'order' => $order,
            'returnBlocked' => $returns->blockedReason($order),
            'returnable' => $returns->returnable($order),
        ]);
    }

    public function invoice(string $token): View
    {
        return $this->invoiceView($this->byToken($token));
    }

    /** Invoice for a logged-in customer's own order. */
    public function invoiceForUser(Request $request, Order $order): View
    {
        abort_unless($order->user_id !== null && $order->user_id === $request->user()->id, 404);

        return $this->invoiceView($order);
    }

    private function invoiceView(Order $order): View
    {
        abort_unless($order->paid_at !== null, 404); // no invoice before the payment is confirmed

        return view('orders.invoice', [
            'order' => $order->load(['items', 'shipping', 'user', 'coupon']),
            'shop' => Setting::current(),
        ]);
    }

    private function byToken(string $token): Order
    {
        abort_unless(strlen($token) === 40, 404);

        return Order::query()->where('public_token', $token)->firstOrFail();
    }
}
