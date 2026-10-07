<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Support\ImageOptimizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_are_cached_and_refreshed_when_changed(): void
    {
        Category::create(['name' => 'Shoes']);
        $this->assertCount(1, Category::cachedAll());
        $this->assertTrue(Cache::has('shop.categories'));

        Category::create(['name' => 'Hats']);

        $this->assertCount(2, Category::cachedAll());
    }

    public function test_settings_are_cached_and_refreshed_when_saved(): void
    {
        $setting = Setting::create(['site_name' => 'Old']);
        $this->assertSame('Old', Setting::current()->site_name);

        $setting->update(['site_name' => 'New']);

        $this->assertSame('New', Setting::current()->site_name);
    }

    public function test_uploaded_photos_become_webp(): void
    {
        if (! function_exists('imagewebp')) {
            $this->markTestSkipped('PHP gd has no WebP support here.');
        }

        Storage::fake('public');
        $img = imagecreatetruecolor(40, 40);
        ob_start();
        imagepng($img);
        Storage::disk('public')->put('products/photo.png', ob_get_clean());

        $new = ImageOptimizer::optimize('products/photo.png');

        $this->assertSame('products/photo.webp', $new);
        Storage::disk('public')->assertExists('products/photo.webp');
        Storage::disk('public')->assertMissing('products/photo.png');
    }

    public function test_a_missing_file_is_left_alone(): void
    {
        Storage::fake('public');

        $this->assertSame('products/none.jpg', ImageOptimizer::optimize('products/none.jpg'));
        $this->assertNull(ImageOptimizer::optimize(null));
    }
}
