<?php

namespace Tests\Feature\Checkout;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;

class PaymentConfirmationTest extends CheckoutTestCase
{
    public function test_the_webhook_confirms_a_payment_exactly_once(): void
    {
        $user = User::factory()->create();
        $order = $this->startCheckout($user, $this->product(['price' => 30]));

        $this->gateway->pay($order->stripe_session_id);
        $this->webhook('checkout.session.completed', $order->stripe_session_id)->assertOk();
        $this->webhook('checkout.session.completed', $order->stripe_session_id)->assertOk(); // Stripe retry

        $order->refresh();
        $this->assertSame('paid', $order->status);
        $this->assertNotNull($order->paid_at);
        $this->assertSame('pi_' . $order->stripe_session_id, $order->payment_intent_id);
        $this->assertSame(30, $user->fresh()->points, 'points must be awarded once');
    }

    public function test_a_webhook_with_a_bad_signature_is_rejected(): void
    {
        $order = $this->startCheckout(User::factory()->create(), $this->product());
        $this->gateway->pay($order->stripe_session_id);

        $this->webhook('checkout.session.completed', $order->stripe_session_id, 'forged')->assertStatus(400);

        $this->assertSame('pending', $order->fresh()->status);
    }

    public function test_a_session_paying_less_than_the_order_total_does_not_confirm_it(): void
    {
        $user = User::factory()->create();
        $order = $this->startCheckout($user, $this->product(['price' => 100]));

        $this->gateway->update($order->stripe_session_id, [
            'status' => 'complete',
            'paymentStatus' => 'paid',
            'amountTotal' => 100, // $1.00 instead of $100.00
        ]);
        $this->webhook('checkout.session.completed', $order->stripe_session_id)->assertOk();

        $this->assertSame('pending', $order->fresh()->status);
        $this->assertSame(0, $user->fresh()->points);
    }

    public function test_an_unpaid_completed_session_does_not_confirm_the_order(): void
    {
        $order = $this->startCheckout(User::factory()->create(), $this->product());

        $this->webhook('checkout.session.completed', $order->stripe_session_id)->assertOk();

        $this->assertSame('pending', $order->fresh()->status);
    }

    public function test_an_expired_session_cancels_the_order_and_gives_everything_back(): void
    {
        $user = User::factory()->create();
        $product = $this->product(['price' => 50, 'stock' => 10]);
        $coupon = Coupon::create(['code' => 'SAVE10', 'type' => 'percent', 'value' => 10, 'usage_limit' => 5]);

        $this->actingAs($user)->post('/apply-coupon', ['code' => 'SAVE10']);
        $order = $this->startCheckout($user, $product, 2);
        $this->assertSame(8, $product->fresh()->stock);
        $this->assertSame(1, $coupon->fresh()->used);

        $this->gateway->update($order->stripe_session_id, ['status' => 'expired']);
        $this->webhook('checkout.session.expired', $order->stripe_session_id)->assertOk();
        $this->webhook('checkout.session.expired', $order->stripe_session_id)->assertOk(); // replay

        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame(10, $product->fresh()->stock, 'stock must be released once, not twice');
        $this->assertSame(0, $coupon->fresh()->used);
    }

    public function test_the_success_page_confirms_through_the_gateway_and_clears_the_cart(): void
    {
        $user = User::factory()->create();
        $order = $this->startCheckout($user, $this->product(['price' => 12]));
        $this->gateway->pay($order->stripe_session_id);

        $this->actingAs($user)
            ->get('/success?session_id=' . $order->stripe_session_id)
            ->assertRedirect('/orders');

        $this->assertSame('paid', $order->fresh()->status);
        $this->assertEmpty(session('cart', []));
    }

    public function test_the_success_page_and_the_webhook_together_award_points_once(): void
    {
        $user = User::factory()->create();
        $order = $this->startCheckout($user, $this->product(['price' => 40]));
        $this->gateway->pay($order->stripe_session_id);

        $this->actingAs($user)->get('/success?session_id=' . $order->stripe_session_id);
        $this->webhook('checkout.session.completed', $order->stripe_session_id)->assertOk();
        $this->actingAs($user)->get('/success?session_id=' . $order->stripe_session_id);

        $this->assertSame(40, $user->fresh()->points);
    }

    public function test_the_success_page_does_not_confirm_an_unpaid_session(): void
    {
        $user = User::factory()->create();
        $order = $this->startCheckout($user, $this->product());

        $this->actingAs($user)->get('/success?session_id=' . $order->stripe_session_id);

        $this->assertSame('pending', $order->fresh()->status);
    }

    public function test_someone_elses_payment_session_cannot_be_claimed(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $order = $this->startCheckout($owner, $this->product());
        $this->gateway->pay($order->stripe_session_id);

        $this->flushSession();
        $this->actingAs($stranger)
            ->get('/success?session_id=' . $order->stripe_session_id)
            ->assertNotFound();
    }

    public function test_the_scheduled_command_cancels_abandoned_orders_and_expires_their_session(): void
    {
        $user = User::factory()->create();
        $product = $this->product(['stock' => 10]);
        $order = $this->startCheckout($user, $product, 3);
        $order->update(['expires_at' => now()->subMinutes(30)]);

        $this->artisan('orders:expire-pending')->assertSuccessful();

        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame(10, $product->fresh()->stock);
        $this->assertContains($order->stripe_session_id, $this->gateway->expired);
    }

    public function test_the_scheduled_command_still_confirms_an_order_that_was_paid_after_all(): void
    {
        $user = User::factory()->create();
        $order = $this->startCheckout($user, $this->product(['price' => 20]));
        $this->gateway->pay($order->stripe_session_id); // webhook never arrived
        $order->update(['expires_at' => now()->subMinutes(30)]);

        $this->artisan('orders:expire-pending')->assertSuccessful();

        $this->assertSame('paid', $order->fresh()->status);
        $this->assertSame(20, $user->fresh()->points);
    }
}
