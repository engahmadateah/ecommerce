<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_english_is_the_default_and_ltr(): void
    {
        $this->get('/')->assertOk()->assertSee('lang="en"', false)->assertSee('dir="ltr"', false);
    }

    public function test_switching_to_arabic_translates_the_page_and_turns_rtl(): void
    {
        $this->get('/locale/ar')->assertRedirect();

        $this->get('/')->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('ابحث عن منتجات', false); // the search box placeholder
    }

    public function test_an_unknown_language_or_currency_is_rejected(): void
    {
        $this->get('/locale/xx')->assertNotFound();
        $this->get('/currency/XXX')->assertNotFound();
    }

    public function test_prices_follow_the_selected_currency(): void
    {
        $category = Category::create(['name' => 'General']);
        Product::create(['name' => 'Blue Shirt', 'price' => 100, 'stock' => 5, 'category_id' => $category->id]);

        $this->get('/products/blue-shirt')->assertSee('$100.00', false);

        $this->get('/currency/SAR');
        $this->get('/products/blue-shirt')->assertSee('SAR 375.00', false)->assertDontSee('$100.00', false);
    }

    public function test_the_cart_warns_that_other_currencies_are_estimates(): void
    {
        $category = Category::create(['name' => 'General']);
        $product = Product::create(['name' => 'Blue Shirt', 'price' => 10, 'stock' => 5, 'category_id' => $category->id]);

        $this->get('/currency/EUR');
        $this->post("/cart/add/{$product->id}");

        $this->get('/cart')->assertOk()->assertSee('You will be charged in USD', false);
    }
}
