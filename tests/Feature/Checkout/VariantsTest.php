<?php

namespace Tests\Feature\Checkout;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;

class VariantsTest extends CheckoutTestCase
{
    private function shirt(): array
    {
        $product = $this->product(['name' => 'Shirt', 'price' => 10, 'stock' => 0]);
        $small = $product->variants()->create(['name' => 'Small', 'stock' => 2]);                 // product price
        $large = $product->variants()->create(['name' => 'Large', 'stock' => 3, 'price' => 25]);  // own price

        return [$product->fresh(), $small, $large];
    }

    public function test_product_stock_is_the_sum_of_its_variants(): void
    {
        [$product, $small, $large] = $this->shirt();

        $this->assertSame(5, $product->stock);

        $large->update(['stock' => 10]);
        $this->assertSame(12, $product->fresh()->stock);

        $small->update(['is_active' => false]);
        $this->assertSame(10, $product->fresh()->stock);
    }

    public function test_a_product_with_options_cannot_be_added_without_choosing_one(): void
    {
        [$product] = $this->shirt();

        $this->post("/cart/add/{$product->id}")
            ->assertRedirect(route('products.show', $product));

        $this->assertEmpty(session('cart', []));
    }

    public function test_the_chosen_option_sets_the_price_and_name_in_the_cart(): void
    {
        [$product, $small, $large] = $this->shirt();

        $this->post("/cart/add/{$product->id}", ['variant_id' => $large->id])->assertSessionHasNoErrors();
        $this->post("/cart/add/{$product->id}", ['variant_id' => $small->id]);

        $cart = session('cart');
        $this->assertSame(25.0, (float) $cart["variant_{$large->id}"]['price']);
        $this->assertSame(10.0, (float) $cart["variant_{$small->id}"]['price']);
        $this->assertSame('Shirt — Large', $cart["variant_{$large->id}"]['name']);
    }

    public function test_an_option_of_another_product_is_refused(): void
    {
        [$product] = $this->shirt();
        $other = $this->product();
        $foreign = $other->variants()->create(['name' => 'X', 'stock' => 5]);

        $this->post("/cart/add/{$product->id}", ['variant_id' => $foreign->id]);

        $this->assertEmpty(session('cart', []));
    }

    public function test_you_cannot_put_more_in_the_cart_than_the_option_has(): void
    {
        [$product, $small] = $this->shirt(); // small has 2

        foreach (range(1, 3) as $i) {
            $this->post("/cart/add/{$product->id}", ['variant_id' => $small->id]);
        }

        $this->assertSame(2, session('cart')["variant_{$small->id}"]['quantity']);
    }

    public function test_checkout_reserves_the_option_stock_and_cancelling_gives_it_back(): void
    {
        [$product, $small, $large] = $this->shirt();
        $user = User::factory()->create();

        $this->actingAs($user)->post("/cart/add/{$product->id}", ['variant_id' => $large->id]);
        $this->actingAs($user)->post("/cart/add/{$product->id}", ['variant_id' => $large->id]);
        $this->actingAs($user)->post('/checkout', $this->shipping())->assertRedirect();

        $order = Order::query()->firstOrFail();
        $item = $order->items()->firstOrFail();

        $this->assertSame($large->id, $item->variant_id);
        $this->assertSame('Shirt — Large', $item->name);
        $this->assertEqualsWithDelta(50.0, (float) $order->total_price, 0.001);
        $this->assertSame(1, $large->fresh()->stock);
        $this->assertSame(3, $product->fresh()->stock); // 2 small + 1 large

        $this->actingAs($user)->get("/checkout/cancel/{$order->id}")->assertRedirect('/cart');

        $this->assertSame(3, $large->fresh()->stock);
        $this->assertSame(5, $product->fresh()->stock);
    }

    public function test_checkout_is_refused_when_the_option_sold_out_after_it_was_added(): void
    {
        [$product, $small] = $this->shirt();
        $user = User::factory()->create();

        $this->actingAs($user)->post("/cart/add/{$product->id}", ['variant_id' => $small->id]);
        $small->update(['stock' => 0]); // someone else bought the rest

        $this->actingAs($user)->post('/checkout', $this->shipping())->assertSessionHas('error');

        $this->assertSame(0, Order::query()->count());
    }

    public function test_the_product_page_offers_the_options(): void
    {
        [$product] = $this->shirt();

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Choose an option')
            ->assertSee('Small')
            ->assertSee('Large');
    }
}
