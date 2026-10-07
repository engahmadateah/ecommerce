<?php

namespace Tests\Feature\Checkout;

use App\Models\Order;
use App\Models\User;

class OrderTrackingTest extends CheckoutTestCase
{
    private function paidGuestOrder(): Order
    {
        $product = $this->product(['price' => 30]);
        $this->post("/cart/add/{$product->id}");
        $this->post('/checkout', $this->shipping() + ['email' => 'guest@shop.test']);

        $order = Order::query()->firstOrFail();
        $this->gateway->pay($order->stripe_session_id);
        $this->webhook('checkout.session.completed', $order->stripe_session_id)->assertOk();

        return $order->fresh();
    }

    public function test_every_order_gets_a_secret_token(): void
    {
        $order = $this->startCheckout(User::factory()->create(), $this->product());

        $this->assertSame(40, strlen($order->public_token));
        $this->assertStringContainsString($order->public_token, $order->trackingUrl());
    }

    public function test_the_secret_link_shows_the_order_without_an_account(): void
    {
        $order = $this->paidGuestOrder();
        $this->flushSession(); // a different browser: only the e-mailed link is available

        $this->get($order->trackingUrl())
            ->assertOk()
            ->assertSee('#'.$order->id)
            ->assertSee('Shipping Information');
    }

    public function test_a_wrong_token_is_not_found(): void
    {
        $this->get('/track/'.str_repeat('a', 40))->assertNotFound();
        $this->get('/track/short')->assertNotFound();
    }

    public function test_lookup_by_order_number_and_email(): void
    {
        $order = $this->paidGuestOrder();

        $this->post('/track', ['order' => $order->id, 'email' => 'GUEST@shop.test'])
            ->assertRedirect($order->trackingUrl());

        $this->post('/track', ['order' => $order->id, 'email' => 'someone@else.test'])
            ->assertSessionHasErrors('order');

        $this->post('/track', ['order' => 99999, 'email' => 'guest@shop.test'])
            ->assertSessionHasErrors('order');
    }

    public function test_the_invoice_is_available_only_after_payment(): void
    {
        $product = $this->product(['price' => 30]);
        $this->post("/cart/add/{$product->id}");
        $this->post('/checkout', $this->shipping() + ['email' => 'guest@shop.test']);
        $pending = Order::query()->firstOrFail();

        $this->get(route('orders.track.invoice', $pending->public_token))->assertNotFound();

        $this->gateway->pay($pending->stripe_session_id);
        $this->webhook('checkout.session.completed', $pending->stripe_session_id)->assertOk();

        $this->get(route('orders.track.invoice', $pending->public_token))
            ->assertOk()
            ->assertSee($pending->fresh()->invoiceNumber())
            ->assertSee('$30.00');
    }

    public function test_a_customer_can_open_only_their_own_invoice(): void
    {
        $owner = User::factory()->create();
        $order = $this->startCheckout($owner, $this->product(['price' => 12]));
        $this->gateway->pay($order->stripe_session_id);
        $this->webhook('checkout.session.completed', $order->stripe_session_id)->assertOk();

        $this->actingAs($owner)->get(route('orders.invoice', $order))->assertOk();
        $this->actingAs(User::factory()->create())->get(route('orders.invoice', $order))->assertNotFound();
        $this->flushSession();
        $this->get(route('orders.invoice', $order))->assertRedirect('/login');
    }
}
