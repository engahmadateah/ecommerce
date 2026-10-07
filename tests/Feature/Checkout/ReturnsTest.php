<?php

namespace Tests\Feature\Checkout;

use App\Exceptions\ReturnException;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\Product;
use App\Models\User;
use App\Services\Returns\ReturnService;
use Illuminate\Support\Facades\Mail;

class ReturnsTest extends CheckoutTestCase
{
    private Product $product;

    private function paidOrder(int $quantity = 2): Order
    {
        $this->product = $this->product(['price' => 10, 'stock' => 10]);
        $order = $this->startCheckout(User::factory()->create(), $this->product, $quantity);
        $this->gateway->pay($order->stripe_session_id);
        $this->webhook('checkout.session.completed', $order->stripe_session_id)->assertOk();

        return $order->fresh();
    }

    private function request(Order $order, int $qty = 1): OrderReturn
    {
        return app(ReturnService::class)->request($order, [$order->items->first()->id => $qty], 'damaged', 'Broken box');
    }

    public function test_a_customer_can_request_a_return_with_the_secret_link(): void
    {
        Mail::fake();
        $order = $this->paidOrder();
        $item = $order->items->first();

        $this->post(route('orders.track.return', $order->public_token), [
            'reason' => 'damaged',
            'details' => 'Broken',
            'quantities' => [$item->id => 1],
        ])->assertRedirect($order->trackingUrl());

        $this->assertDatabaseHas('order_returns', ['order_id' => $order->id, 'status' => 'requested', 'reason' => 'damaged']);

        $this->get($order->trackingUrl())->assertOk()->assertSee('Under review');
    }

    public function test_an_unpaid_order_cannot_be_returned(): void
    {
        $order = $this->startCheckout(User::factory()->create(), $this->product());

        $this->post(route('orders.track.return', $order->public_token), [
            'reason' => 'damaged',
            'quantities' => [$order->items->first()->id => 1],
        ])->assertSessionHasErrors('return');

        $this->assertDatabaseCount('order_returns', 0);
    }

    public function test_you_cannot_return_more_than_you_bought_or_nothing(): void
    {
        $order = $this->paidOrder(2);
        $service = app(ReturnService::class);
        $itemId = $order->items->first()->id;

        $this->expectException(ReturnException::class);
        $service->request($order, [$itemId => 3], 'damaged', null);
    }

    public function test_an_empty_selection_is_rejected(): void
    {
        $order = $this->paidOrder();

        $this->expectException(ReturnException::class);
        app(ReturnService::class)->request($order, [$order->items->first()->id => 0], 'other', null);
    }

    public function test_only_one_open_request_per_order(): void
    {
        $order = $this->paidOrder(2);
        $this->request($order);

        $this->expectException(ReturnException::class);
        $this->request($order);
    }

    public function test_the_return_window_closes(): void
    {
        $order = $this->paidOrder();
        $order->forceFill(['paid_at' => now()->subDays(30)])->save();

        $this->expectException(ReturnException::class);
        $this->request($order->fresh());
    }

    public function test_refund_goes_through_the_gateway_and_restocks(): void
    {
        Mail::fake();
        $order = $this->paidOrder(2); // $20, stock 10 -> 8
        $this->assertSame(8, $this->product->fresh()->stock);

        $service = app(ReturnService::class);
        $return = $this->request($order, 1);

        $this->assertSame(1000, $service->suggestedRefundCents($return));

        $service->refund($return, 1000, true, 'Sorry about that');

        $this->assertSame([['intent' => $order->payment_intent_id, 'amount' => 1000, 'key' => 'return-'.$return->id]], $this->gateway->refunds);
        $this->assertSame('refunded', $return->fresh()->status);
        $this->assertSame('10.00', $order->fresh()->refunded_total);
        $this->assertSame('paid', $order->fresh()->status); // partial: order stays paid
        $this->assertSame(9, $this->product->fresh()->stock);
    }

    public function test_a_full_refund_marks_the_order_refunded(): void
    {
        $order = $this->paidOrder(1);
        $return = $this->request($order, 1);

        app(ReturnService::class)->refund($return, 1000, false);

        $this->assertSame('refunded', $order->fresh()->status);
        $this->assertSame(9, $this->product->fresh()->stock);
    }

    public function test_a_return_cannot_be_refunded_twice(): void
    {
        $order = $this->paidOrder(2);
        $return = $this->request($order, 1);
        $service = app(ReturnService::class);
        $service->refund($return, 1000, false);

        try {
            $service->refund($return->fresh(), 1000, false);
            $this->fail('Second refund should be refused');
        } catch (ReturnException) {
            $this->assertCount(1, $this->gateway->refunds);
        }
    }

    public function test_refund_cannot_exceed_what_was_paid(): void
    {
        $order = $this->paidOrder(1);
        $return = $this->request($order, 1);

        $this->expectException(ReturnException::class);
        app(ReturnService::class)->refund($return, 1001, false);
    }

    public function test_a_gateway_failure_changes_nothing(): void
    {
        $order = $this->paidOrder(2);
        $return = $this->request($order, 1);
        $this->gateway->failRefund = true;

        try {
            app(ReturnService::class)->refund($return, 1000, true);
            $this->fail('Should have thrown');
        } catch (\RuntimeException) {
        }

        $this->assertSame('requested', $return->fresh()->status);
        $this->assertSame('0.00', $order->fresh()->refunded_total);
        $this->assertSame(8, $this->product->fresh()->stock);
    }

    public function test_rejecting_closes_the_request_without_refunding(): void
    {
        $order = $this->paidOrder(2);
        $return = $this->request($order, 1);

        app(ReturnService::class)->reject($return, 'Outside policy');

        $this->assertSame('rejected', $return->fresh()->status);
        $this->assertSame([], $this->gateway->refunds);

        // After a rejection the customer may ask again.
        $this->assertNull(app(ReturnService::class)->blockedReason($order->fresh()));
    }
}
