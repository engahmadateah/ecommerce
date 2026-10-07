<?php

namespace Tests\Feature;

use App\Mail\LowStockMail;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Support\OrderCsv;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminToolsTest extends TestCase
{
    use RefreshDatabase;

    private function product(int $stock, string $name = 'Lamp'): Product
    {
        $category = Category::query()->first() ?? Category::create(['name' => 'General']);

        return Product::create(['name' => $name, 'price' => 10, 'stock' => $stock, 'category_id' => $category->id]);
    }

    public function test_low_stock_alert_lists_only_products_running_out(): void
    {
        Mail::fake();
        config(['mail.admin_address' => 'owner@shop.test', 'shop.low_stock_threshold' => 5]);
        $this->product(2, 'Almost gone');
        $this->product(50, 'Plenty');

        $this->artisan('stock:alert')->assertSuccessful();

        Mail::assertSent(LowStockMail::class, fn (LowStockMail $m) => $m->hasTo('owner@shop.test')
            && $m->products->pluck('name')->all() === ['Almost gone']);
    }

    public function test_no_alert_when_everything_is_in_stock(): void
    {
        Mail::fake();
        config(['mail.admin_address' => 'owner@shop.test']);
        $this->product(50);

        $this->artisan('stock:alert')->assertSuccessful();

        Mail::assertNothingSent();
    }

    public function test_admin_changes_are_written_to_the_activity_log(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = $this->product(10);

        $this->actingAs($admin);
        $product->update(['price' => 25]);

        $log = ActivityLog::query()->where('action', 'updated')->firstOrFail();
        $this->assertSame($admin->id, $log->user_id);
        $this->assertSame('Product', $log->subject_type);
        $this->assertEquals([10.0, 25.0], array_map('floatval', $log->changes['price']));
    }

    public function test_changes_by_customers_or_jobs_are_not_logged(): void
    {
        $product = $this->product(10); // nobody logged in

        $this->actingAs(User::factory()->create(['role' => 'user']));
        $product->update(['price' => 99]);

        $this->assertSame(0, ActivityLog::count());
    }

    public function test_csv_export_has_a_header_and_one_row_per_order_and_blocks_formulas(): void
    {
        $user = User::factory()->create(['name' => '=HYPERLINK("x")']);
        Order::create(['user_id' => $user->id, 'status' => 'paid', 'total_price' => 30, 'subtotal' => 30, 'paid_at' => now()]);

        ob_start();
        OrderCsv::stream()->sendContent();
        $csv = ob_get_clean();

        $this->assertStringStartsWith("\xEF\xBB\xBFOrder,Date,Status", $csv);
        $this->assertSame(2, count(array_filter(explode("\n", trim($csv)))));
        $this->assertStringContainsString("'=HYPERLINK", $csv);
        $this->assertStringContainsString('30.00', $csv);
    }
}
