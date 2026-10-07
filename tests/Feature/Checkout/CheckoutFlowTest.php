<?php

namespace Tests\Feature\Checkout;

use App\Exceptions\CheckoutException;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use App\Services\CartService;
use App\Services\Checkout\QuoteLine;
use App\Services\Checkout\StockService;
use Illuminate\Support\Facades\DB;

class CheckoutFlowTest extends CheckoutTestCase
{
    public function test_checkout_creates_a_pending_order_reserves_stock_and_redirects_to_stripe(): void
    {
        $user = User::factory()->create();
        $product = $this->product(['price' => 20, 'stock' => 5]);

        $this->addToCart($user, $product, 2);
        $response = $this->actingAs($user)->post('/checkout', $this->shipping());

        $order = Order::query()->firstOrFail();

        $response->assertRedirect('https://checkout.stripe.test/' . $order->id);
        $this->assertSame('pending', $order->status);
        $this->assertEquals(40.00, (float) $order->total_price);
        $this->assertSame('cs_test_' . $order->id, $order->stripe_session_id);
        $this->assertSame(3, $product->fresh()->stock);
        $this->assertDatabaseHas('shippings', ['order_id' => $order->id, 'city' => 'Riyadh']);
        $this->assertNotEmpty(session('cart'), 'the cart must survive until the payment is confirmed');
    }

    public function test_the_second_buyer_of_the_last_unit_is_stopped_without_creating_an_order(): void
    {
        $product = $this->product(['stock' => 1]);
        $alice = User::factory()->create();
        $bob = User::factory()->create();

        $this->addToCart($alice, $product);
        $aliceCart = session('cart');
        $this->flushSession();

        $this->addToCart($bob, $product);
        $bobCart = session('cart');
        $this->flushSession();

        $this->withSession(['cart' => $aliceCart])
            ->actingAs($alice)->post('/checkout', $this->shipping())->assertRedirect();

        $this->flushSession();
        $this->withSession(['cart' => $bobCart])
            ->actingAs($bob)->post('/checkout', $this->shipping())
            ->assertSessionHas('error');

        $this->assertSame(1, Order::query()->count());
        $this->assertSame(0, $product->fresh()->stock);
    }

    public function test_stock_reservation_is_all_or_nothing(): void
    {
        $plenty = $this->product(['stock' => 5]);
        $scarce = $this->product(['stock' => 2]);

        $lines = [
            new QuoteLine('a', 'A', 1000, 1, $plenty->id),
            new QuoteLine('b', 'B', 1000, 3, $scarce->id),
        ];

        try {
            DB::transaction(fn () => app(StockService::class)->reserve($lines));
            $this->fail('Reserving more than the available stock must fail.');
        } catch (CheckoutException) {
            // expected
        }

        $this->assertSame(5, $plenty->fresh()->stock);
        $this->assertSame(2, $scarce->fresh()->stock);
    }

    public function test_checkout_is_refused_when_the_price_changed_after_adding_to_cart(): void
    {
        $user = User::factory()->create();
        $product = $this->product(['price' => 10]);

        $this->addToCart($user, $product);
        $product->update(['price' => 12]);

        $this->actingAs($user)->post('/checkout', $this->shipping())->assertSessionHas('error');

        $this->assertSame(0, Order::query()->count());
        $this->assertSame(10, $product->fresh()->stock);
    }

    public function test_the_cart_always_reads_prices_from_the_database(): void
    {
        $product = $this->product(['price' => 10]);
        $this->withSession(['cart' => [$product->id => ['name' => 'stale', 'price' => 1, 'quantity' => 2]]]);

        $cart = app(CartService::class)->getCart();

        $this->assertEquals(10.0, $cart[$product->id]['price']);
        $this->assertSame($product->name, $cart[$product->id]['name']);
        $this->assertSame(2, $cart[$product->id]['quantity']);
    }

    public function test_a_percent_coupon_is_calculated_on_the_server_and_consumed_once(): void
    {
        $user = User::factory()->create();
        $product = $this->product(['price' => 50]);
        $coupon = Coupon::create(['code' => 'SAVE10', 'type' => 'percent', 'value' => 10, 'usage_limit' => 5]);

        $this->actingAs($user)->post('/apply-coupon', ['code' => 'SAVE10'])->assertSessionHas('success');
        $order = $this->startCheckout($user, $product);

        $this->assertEquals(50.00, (float) $order->subtotal);
        $this->assertEquals(5.00, (float) $order->discount_total);
        $this->assertEquals(45.00, (float) $order->total_price);
        $this->assertSame($coupon->id, $order->coupon_id);
        $this->assertSame(1, $coupon->fresh()->used);
    }

    public function test_a_coupon_at_its_usage_limit_cannot_be_applied(): void
    {
        $user = User::factory()->create();
        Coupon::create(['code' => 'ONCE', 'type' => 'fixed', 'value' => 5, 'usage_limit' => 1, 'used' => 1]);

        $this->actingAs($user)->post('/apply-coupon', ['code' => 'ONCE'])
            ->assertSessionHas('error')
            ->assertSessionMissing('coupon_id');
    }

    public function test_a_coupon_that_covers_everything_skips_the_payment_page(): void
    {
        $user = User::factory()->create();
        $product = $this->product(['price' => 20, 'stock' => 4]);
        Coupon::create(['code' => 'FREE', 'type' => 'fixed', 'value' => 50]);

        $this->actingAs($user)->post('/apply-coupon', ['code' => 'FREE']);
        $this->addToCart($user, $product);

        $this->actingAs($user)->post('/checkout', $this->shipping())->assertRedirect('/orders');

        $order = Order::query()->firstOrFail();
        $this->assertSame('paid', $order->status);
        $this->assertEquals(0.00, (float) $order->total_price);
        $this->assertSame([], $this->gateway->sessions);
        $this->assertSame(3, $product->fresh()->stock);
        $this->assertEmpty(session('cart', []));
    }

    public function test_a_bundle_can_be_bought_and_paid(): void
    {
        $user = User::factory()->create();
        $package = Package::create(['name' => 'Starter Bundle', 'price' => 25]);

        $this->actingAs($user)->post("/packages/add/{$package->id}");
        $this->actingAs($user)->post('/checkout', $this->shipping())->assertRedirect();

        $order = Order::query()->firstOrFail();
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => null,
            'package_id' => $package->id,
            'name' => 'Starter Bundle',
        ]);

        $this->gateway->pay($order->stripe_session_id);
        $this->webhook('checkout.session.completed', $order->stripe_session_id)->assertOk();

        $this->assertSame('paid', $order->fresh()->status);
        $this->assertSame(25, $user->fresh()->points);
    }

    public function test_a_payment_page_failure_gives_the_reserved_stock_back(): void
    {
        $user = User::factory()->create();
        $product = $this->product(['stock' => 10]);
        $this->gateway->failCreate = true;

        $this->addToCart($user, $product, 3);
        $this->actingAs($user)->post('/checkout', $this->shipping())->assertSessionHas('error');

        $this->assertSame('cancelled', Order::query()->firstOrFail()->status);
        $this->assertSame(10, $product->fresh()->stock);
    }

    public function test_cancelling_on_the_payment_page_releases_stock_and_keeps_the_cart(): void
    {
        $user = User::factory()->create();
        $product = $this->product(['stock' => 10]);
        $order = $this->startCheckout($user, $product, 2);
        $this->assertSame(8, $product->fresh()->stock);

        $this->actingAs($user)->get("/checkout/cancel/{$order->id}")->assertRedirect('/cart');

        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame(10, $product->fresh()->stock);
        $this->assertNotEmpty(session('cart'));
    }

    public function test_retrying_checkout_does_not_keep_stock_locked_by_the_previous_attempt(): void
    {
        $user = User::factory()->create();
        $product = $this->product(['stock' => 10]);

        $first = $this->startCheckout($user, $product, 2);
        $this->actingAs($user)->post('/checkout', $this->shipping())->assertRedirect();

        $this->assertSame('cancelled', $first->fresh()->status);
        $this->assertSame(8, $product->fresh()->stock);
        $this->assertSame(1, Order::query()->where('status', 'pending')->count());
    }
}
