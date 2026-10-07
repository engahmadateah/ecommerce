<?php

namespace Tests\Feature\Checkout;

use App\Mail\NewOrderAdminMail;
use App\Mail\OrderPaidMail;
use App\Mail\OrderShippedMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class OrderEmailsTest extends CheckoutTestCase
{
    public function test_paying_sends_one_confirmation_to_the_customer_and_one_to_the_owner(): void
    {
        config(['mail.admin_address' => 'owner@shop.test']);
        Mail::fake();

        $user = User::factory()->create(['email' => 'buyer@shop.test']);
        $order = $this->startCheckout($user, $this->product(['price' => 30]));

        $this->gateway->pay($order->stripe_session_id);
        $this->webhook('checkout.session.completed', $order->stripe_session_id)->assertOk();
        $this->webhook('checkout.session.completed', $order->stripe_session_id)->assertOk(); // Stripe retry

        Mail::assertSent(OrderPaidMail::class, 1);
        Mail::assertSent(OrderPaidMail::class, fn ($m) => $m->hasTo('buyer@shop.test'));
        Mail::assertSent(NewOrderAdminMail::class, 1);
        Mail::assertSent(NewOrderAdminMail::class, fn ($m) => $m->hasTo('owner@shop.test'));
    }

    public function test_no_email_is_sent_while_the_order_is_still_pending(): void
    {
        Mail::fake();

        $this->startCheckout(User::factory()->create(), $this->product());

        Mail::assertNothingSent();
    }

    public function test_moving_an_order_to_shipped_notifies_the_customer_once(): void
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'buyer@shop.test']);
        $order = $this->startCheckout($user, $this->product());
        $this->gateway->pay($order->stripe_session_id);
        $this->webhook('checkout.session.completed', $order->stripe_session_id)->assertOk();

        $order->refresh()->update(['status' => 'shipped']);
        $order->update(['total_price' => $order->total_price]); // unrelated save: no second mail

        Mail::assertSent(OrderShippedMail::class, 1);
        Mail::assertSent(OrderShippedMail::class, fn ($m) => $m->hasTo('buyer@shop.test'));
    }

    public function test_a_failing_mail_server_does_not_break_the_payment(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $order = $this->startCheckout(User::factory()->create(), $this->product());
        $this->gateway->pay($order->stripe_session_id);
        $this->webhook('checkout.session.completed', $order->stripe_session_id)->assertOk();

        $this->assertSame('paid', $order->fresh()->status);
    }
}
