<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Package;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_page_renders_with_items_a_coupon_and_a_bundle_suggestion(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'General']);
        $product = Product::create(['name' => 'Widget', 'price' => 15, 'stock' => 5, 'category_id' => $category->id]);
        Coupon::create(['code' => 'HELLO10', 'type' => 'percent', 'value' => 10, 'usage_limit' => 100]);

        $this->actingAs($user)->post("/cart/add/{$product->id}")->assertRedirect();
        $this->actingAs($user)->get('/cart')->assertOk()->assertSee('Widget');
    }

    public function test_orders_page_renders_including_a_bundle_order(): void
    {
        $user = User::factory()->create();
        $package = Package::create(['name' => 'Bundle X', 'price' => 30]);
        $order = $user->orders()->create(['status' => 'paid', 'total_price' => 30, 'subtotal' => 30]);
        $order->items()->create(['package_id' => $package->id, 'name' => 'Bundle X', 'quantity' => 1, 'price' => 30]);

        $this->actingAs($user)->get('/orders')->assertOk()->assertSee('Bundle X');
    }

    public function test_packages_pages_render(): void
    {
        $package = Package::create(['name' => 'Bundle Y', 'price' => 40]);

        $this->get('/packages')->assertOk()->assertSee('Bundle Y');
        $this->get("/packages/{$package->id}")->assertOk();
    }

    public function test_checkout_form_is_open_to_guests(): void
    {
        $this->get('/checkout')->assertOk()->assertSee('name="email"', false);
    }
}
