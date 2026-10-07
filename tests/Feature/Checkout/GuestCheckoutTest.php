<?php

namespace Tests\Feature\Checkout;

use App\Mail\OrderPaidMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class GuestCheckoutTest extends CheckoutTestCase
{
    private function guestCheckout(array $extra = [], int $price = 25)
    {
        $product = $this->product(['price' => $price]);
        $this->post("/cart/add/{$product->id}");

        return $this->post('/checkout', $this->shipping() + $extra);
    }

    public function test_a_guest_can_check_out_with_just_an_email(): void
    {
        $this->guestCheckout(['email' => 'guest@shop.test'])->assertRedirect();

        $order = Order::query()->firstOrFail();
        $this->assertNull($order->user_id);
        $this->assertSame('guest@shop.test', $order->guest_email);
        $this->assertSame('pending', $order->status);
        $this->assertNotNull($order->stripe_session_id);
    }

    public function test_a_guest_must_give_an_email(): void
    {
        $this->guestCheckout()->assertSessionHasErrors('email');

        $this->assertSame(0, Order::query()->count());
    }

    public function test_the_guest_pays_sees_the_thank_you_page_and_gets_the_email(): void
    {
        Mail::fake();
        $this->guestCheckout(['email' => 'guest@shop.test']);
        $order = Order::query()->firstOrFail();

        $this->gateway->pay($order->stripe_session_id);
        $this->get('/success?session_id='.$order->stripe_session_id)
            ->assertOk()
            ->assertSee('Payment Successful')
            ->assertSee('guest@shop.test');

        $this->assertSame('paid', $order->fresh()->status);
        Mail::assertSent(OrderPaidMail::class, fn ($m) => $m->hasTo('guest@shop.test'));
    }

    public function test_another_visitor_cannot_open_a_guests_payment_result(): void
    {
        $this->guestCheckout(['email' => 'guest@shop.test']);
        $order = Order::query()->firstOrFail();

        $this->flushSession(); // a different browser
        $this->get('/success?session_id='.$order->stripe_session_id)->assertNotFound();

        $this->actingAs(User::factory()->create())
            ->get('/success?session_id='.$order->stripe_session_id)
            ->assertNotFound();
    }

    public function test_a_guest_can_cancel_their_own_payment_and_keeps_the_cart(): void
    {
        $this->guestCheckout(['email' => 'guest@shop.test']);
        $order = Order::query()->firstOrFail();

        $this->get("/checkout/cancel/{$order->id}")->assertRedirect('/cart');

        $this->assertSame('cancelled', $order->fresh()->status);
    }

    public function test_a_logged_in_customer_still_checks_out_normally(): void
    {
        $user = User::factory()->create();
        $order = $this->startCheckout($user, $this->product());

        $this->assertSame($user->id, $order->user_id);
        $this->assertNull($order->guest_email);
    }
}
