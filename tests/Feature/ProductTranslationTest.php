<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTranslationTest extends TestCase
{
    use RefreshDatabase;

    private function product(array $extra = []): Product
    {
        $category = Category::create(['name' => 'Mugs', 'translations' => ['ar' => ['name' => 'أكواب']]]);

        return Product::create($extra + [
            'name' => 'Blue Mug',
            'description' => 'A nice mug',
            'price' => 10,
            'stock' => 5,
            'category_id' => $category->id,
            'translations' => ['ar' => ['name' => 'كوب أزرق', 'description' => 'كوب جميل'], 'fr' => ['name' => '']],
        ]);
    }

    public function test_empty_translations_are_dropped_and_arabic_is_stored_readable(): void
    {
        $product = $this->product();

        $this->assertSame(['ar' => ['name' => 'كوب أزرق', 'description' => 'كوب جميل']], $product->fresh()->translations);
        $this->assertStringContainsString('كوب أزرق', $product->getRawOriginal('translations'));
    }

    public function test_text_follows_the_language_and_falls_back_to_english(): void
    {
        $product = $this->product();

        $this->assertSame('Blue Mug', $product->localized_name);

        app()->setLocale('ar');
        $this->assertSame('كوب أزرق', $product->localized_name);
        $this->assertSame('كوب جميل', $product->localized_description);

        $plain = $this->product(['name' => 'Red Mug', 'translations' => null, 'slug' => 'red-mug']);
        $this->assertSame('Red Mug', $plain->localized_name);
    }

    public function test_the_storefront_shows_the_arabic_name(): void
    {
        $product = $this->product();

        $this->withSession(['locale' => 'ar'])->get('/products/'.$product->slug)
            ->assertOk()
            ->assertSee('كوب أزرق')
            ->assertSee('كوب جميل');
    }

    public function test_arabic_search_finds_the_product(): void
    {
        $this->product();

        $this->withSession(['locale' => 'ar'])->get('/?search='.urlencode('أزرق'))
            ->assertOk()
            ->assertSee('كوب أزرق');
    }

    public function test_the_cart_shows_the_translated_name_but_keeps_the_base_name(): void
    {
        $product = $this->product();

        $this->post("/cart/add/{$product->id}");

        $this->withSession(['locale' => 'ar'])->get('/cart')->assertSee('كوب أزرق');
        $this->withSession(['locale' => 'en'])->get('/cart')->assertSee('Blue Mug');
    }
}
