<?php

namespace Tests\Feature\Checkout;

use App\Models\Setting;
use App\Models\User;

class ShippingAndTaxTest extends CheckoutTestCase
{
    private function charged(int $orderId): int
    {
        return $this->gateway->sessions['cs_test_'.$orderId]->amountTotal;
    }

    public function test_nothing_extra_is_charged_until_rules_are_configured(): void
    {
        $order = $this->startCheckout(User::factory()->create(), $this->product(['price' => 20]));

        $this->assertEqualsWithDelta(20.0, (float) $order->total_price, 0.001);
        $this->assertEqualsWithDelta(0.0, (float) $order->shipping_total, 0.001);
        $this->assertEqualsWithDelta(0.0, (float) $order->tax_total, 0.001);
    }

    public function test_a_flat_shipping_fee_is_added_below_the_free_shipping_amount(): void
    {
        Setting::create(['shipping_fee' => 5, 'free_shipping_threshold' => 50]);

        $order = $this->startCheckout(User::factory()->create(), $this->product(['price' => 20]));

        $this->assertEqualsWithDelta(5.0, (float) $order->shipping_total, 0.001);
        $this->assertEqualsWithDelta(25.0, (float) $order->total_price, 0.001);
        $this->assertSame(2500, $this->charged($order->id), 'the payment page must charge goods + shipping');
    }

    public function test_shipping_is_free_from_the_threshold(): void
    {
        Setting::create(['shipping_fee' => 5, 'free_shipping_threshold' => 50]);

        $order = $this->startCheckout(User::factory()->create(), $this->product(['price' => 60]));

        $this->assertEqualsWithDelta(0.0, (float) $order->shipping_total, 0.001);
        $this->assertEqualsWithDelta(60.0, (float) $order->total_price, 0.001);
    }

    public function test_the_cart_tells_how_much_is_missing_for_free_shipping(): void
    {
        Setting::create(['shipping_fee' => 5, 'free_shipping_threshold' => 50]);
        $product = $this->product(['price' => 20]);

        $this->post("/cart/add/{$product->id}");

        $this->get('/cart')->assertOk()->assertSee('more for free shipping', false)->assertSee('$30.00', false);
    }

    public function test_tax_that_is_not_included_is_added_on_top(): void
    {
        Setting::create(['tax_rate' => 10, 'tax_included' => false]);

        $order = $this->startCheckout(User::factory()->create(), $this->product(['price' => 100]));

        $this->assertEqualsWithDelta(10.0, (float) $order->tax_total, 0.001);
        $this->assertFalse($order->tax_included);
        $this->assertEqualsWithDelta(110.0, (float) $order->total_price, 0.001);
        $this->assertSame(11000, $this->charged($order->id));
    }

    public function test_tax_that_is_included_does_not_change_the_total(): void
    {
        Setting::create(['tax_rate' => 20, 'tax_included' => true]);

        $order = $this->startCheckout(User::factory()->create(), $this->product(['price' => 120]));

        $this->assertEqualsWithDelta(20.0, (float) $order->tax_total, 0.001);
        $this->assertTrue($order->tax_included);
        $this->assertEqualsWithDelta(120.0, (float) $order->total_price, 0.001);
    }

    public function test_tax_is_also_charged_on_shipping(): void
    {
        Setting::create(['shipping_fee' => 10, 'tax_rate' => 10, 'tax_included' => false]);

        $order = $this->startCheckout(User::factory()->create(), $this->product(['price' => 90]));

        // goods 90 + shipping 10 = 100 → tax 10 → 110
        $this->assertEqualsWithDelta(110.0, (float) $order->total_price, 0.001);
    }

    public function test_the_paid_amount_must_match_so_a_payment_with_shipping_confirms_the_order(): void
    {
        Setting::create(['shipping_fee' => 5, 'tax_rate' => 10, 'tax_included' => false]);

        $order = $this->startCheckout(User::factory()->create(), $this->product(['price' => 45]));
        $this->gateway->pay($order->stripe_session_id);
        $this->webhook('checkout.session.completed', $order->stripe_session_id)->assertOk();

        // (45 + 5) * 1.10 = 55
        $this->assertEqualsWithDelta(55.0, (float) $order->fresh()->total_price, 0.001);
        $this->assertSame('paid', $order->fresh()->status);
    }
}
