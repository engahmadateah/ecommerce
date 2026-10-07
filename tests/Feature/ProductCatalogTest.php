<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    private function make(array $attributes = []): Product
    {
        $category = Category::query()->first() ?? Category::create(['name' => 'General']);

        return Product::create($attributes + [
            'name' => 'Blue Shirt',
            'price' => 20,
            'stock' => 5,
            'category_id' => $category->id,
        ]);
    }

    public function test_a_product_gets_a_unique_slug(): void
    {
        $a = $this->make();
        $b = $this->make();

        $this->assertSame('blue-shirt', $a->slug);
        $this->assertSame('blue-shirt-2', $b->slug);
    }

    public function test_the_product_page_opens_by_slug_and_by_old_numeric_id(): void
    {
        $product = $this->make();

        $this->get('/products/blue-shirt')->assertOk();
        $this->get('/products/'.$product->id)->assertOk();
        $this->assertStringEndsWith('/products/blue-shirt', route('products.show', $product));
    }

    public function test_a_draft_product_is_hidden_everywhere(): void
    {
        $draft = $this->make(['name' => 'Secret Hat', 'is_published' => false]);

        $this->get('/products/'.$draft->slug)->assertNotFound();
        $this->get('/')->assertOk()->assertDontSee('Secret Hat');
        $this->get('/products/search?search=Secret')->assertOk()->assertDontSee('Secret Hat');
    }

    public function test_a_draft_product_cannot_be_added_to_the_cart(): void
    {
        $draft = $this->make(['is_published' => false]);
        $user = User::factory()->create();

        $this->actingAs($user)->post('/cart/add/'.$draft->id);

        $this->assertEmpty(session('cart', []));
    }

    public function test_the_gallery_lists_the_main_image_first(): void
    {
        $product = $this->make(['image' => 'products/a.jpg', 'gallery' => ['products/b.jpg', 'products/c.jpg']]);

        $this->assertSame(['products/a.jpg', 'products/b.jpg', 'products/c.jpg'], $product->all_images);
    }
}
