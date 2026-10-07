<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    private function make(array $attributes = []): Product
    {
        $category = Category::query()->first() ?? Category::create(['name' => 'General']);

        return Product::create($attributes + [
            'name' => 'Blue Shirt',
            'description' => 'A very comfortable blue shirt.',
            'price' => 20,
            'stock' => 5,
            'category_id' => $category->id,
        ]);
    }

    public function test_the_product_page_has_its_own_title_description_and_structured_data(): void
    {
        $this->make();

        $this->get('/products/blue-shirt')
            ->assertOk()
            ->assertSee('<title>Blue Shirt |', false)
            ->assertSee('A very comfortable blue shirt.', false)
            ->assertSee('application/ld+json', false)
            ->assertSee('https://schema.org/InStock', false)
            ->assertSee('<link rel="canonical"', false);
    }

    public function test_private_pages_are_marked_noindex(): void
    {
        $this->get('/cart')->assertOk()->assertSee('noindex,nofollow', false);
    }

    public function test_the_sitemap_lists_published_products_only(): void
    {
        $this->make();
        $this->make(['name' => 'Secret Hat', 'is_published' => false]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/products/blue-shirt', false)
            ->assertDontSee('secret-hat', false);
    }

    public function test_robots_points_to_the_sitemap_and_hides_private_areas(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap: ', false)
            ->assertSee('Disallow: /admin', false);
    }
}
