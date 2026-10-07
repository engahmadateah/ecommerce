<?php

namespace Tests\Feature\Checkout;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Payment\PaymentGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\FakePaymentGateway;
use Tests\TestCase;

abstract class CheckoutTestCase extends TestCase
{
    use RefreshDatabase;

    protected FakePaymentGateway $gateway;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gateway = new FakePaymentGateway();
        $this->app->instance(PaymentGateway::class, $this->gateway);
    }

    protected function product(array $attributes = []): Product
    {
        static $counter = 0;
        $counter++;

        $category = Category::query()->first() ?? Category::create(['name' => 'General']);

        return Product::create($attributes + [
            'name' => "Product {$counter}",
            'price' => 10,
            'stock' => 10,
            'category_id' => $category->id,
        ]);
    }

    protected function shipping(): array
    {
        return [
            'full_name' => 'Jane Doe',
            'phone' => '0500000000',
            'address_line' => '1 Main Street',
            'city' => 'Riyadh',
        ];
    }

    protected function addToCart(User $user, Product $product, int $times = 1): void
    {
        for ($i = 0; $i < $times; $i++) {
            $this->actingAs($user)->post("/cart/add/{$product->id}");
        }
    }

    /** Fills the cart and runs checkout; returns the pending order it created. */
    protected function startCheckout(User $user, Product $product, int $quantity = 1): Order
    {
        $this->addToCart($user, $product, $quantity);
        $this->actingAs($user)->post('/checkout', $this->shipping())->assertRedirect();

        return Order::query()->latest('id')->firstOrFail();
    }

    protected function webhook(string $type, string $sessionId, string $signature = 'valid')
    {
        return $this->withHeaders(['Stripe-Signature' => $signature])
            ->postJson('/stripe/webhook', ['type' => $type, 'session_id' => $sessionId]);
    }
}
