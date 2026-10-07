<?php

namespace Tests\Feature\Checkout;

use App\Mail\AbandonedCartMail;
use App\Models\AbandonedCart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AbandonedCartTest extends CheckoutTestCase
{
    public function test_a_logged_in_cart_is_remembered_and_cleared_when_emptied(): void
    {
        $user = User::factory()->create();
        $product = $this->product();

        $this->addToCart($user, $product, 2);

        $saved = AbandonedCart::where('user_id', $user->id)->firstOrFail();
        $this->assertSame(2, $saved->items[0]['quantity']);

        $this->actingAs($user)->delete("/cart/{$product->id}");
        $this->assertDatabaseMissing('abandoned_carts', ['user_id' => $user->id]);
    }

    public function test_the_reminder_goes_out_once_after_the_delay(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->addToCart($user, $this->product());

        $this->artisan('carts:remind')->assertSuccessful();
        Mail::assertNothingSent(); // too early

        $this->travel(4)->hours();
        $this->artisan('carts:remind')->assertSuccessful();
        Mail::assertSent(AbandonedCartMail::class, 1);

        $this->artisan('carts:remind')->assertSuccessful();
        Mail::assertSent(AbandonedCartMail::class, 1); // still just one
    }

    public function test_no_reminder_after_a_purchase_or_an_opt_out(): void
    {
        Mail::fake();
        $buyer = User::factory()->create();
        $quiet = User::factory()->create();
        $this->addToCart($buyer, $this->product());
        $this->addToCart($quiet, $this->product());

        $this->travel(1)->minute();
        Order::create(['user_id' => $buyer->id, 'status' => 'paid', 'total_price' => 10, 'subtotal' => 10, 'paid_at' => now()]);
        AbandonedCart::where('user_id', $quiet->id)->update(['opted_out' => true]);

        $this->travel(4)->hours();
        $this->artisan('carts:remind')->assertSuccessful();

        Mail::assertNothingSent();
    }

    public function test_the_link_in_the_email_restores_the_cart(): void
    {
        $user = User::factory()->create();
        $product = $this->product(['name' => 'Blue Mug']);
        $this->addToCart($user, $product, 2);
        $token = AbandonedCart::firstOrFail()->token;

        $this->flushSession();
        $this->app["auth"]->forgetGuards();

        $this->get("/cart/restore/{$token}")->assertRedirect('/cart');
        $this->get('/cart')->assertSee('Blue Mug');
    }

    public function test_a_wrong_token_is_a_404_and_stop_link_opts_out(): void
    {
        $this->get('/cart/restore/'.str_repeat('a', 40))->assertNotFound();
        $this->get('/cart/restore/short')->assertNotFound();

        $user = User::factory()->create();
        $this->addToCart($user, $this->product());
        $cart = AbandonedCart::firstOrFail();

        $this->get("/cart/reminders/stop/{$cart->token}")->assertRedirect('/');
        $this->assertTrue($cart->fresh()->opted_out);
    }
}
