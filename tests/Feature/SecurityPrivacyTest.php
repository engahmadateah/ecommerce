<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityPrivacyTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_page_gets_the_basic_security_headers(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy');
        $response->assertHeader('Content-Security-Policy-Report-Only');
        $this->assertStringContainsString("object-src 'none'", $response->headers->get('Content-Security-Policy-Report-Only'));
    }

    public function test_csp_can_be_enforced(): void
    {
        config(['security.csp.report_only' => false]);

        $this->get('/')->assertHeader('Content-Security-Policy');
    }

    public function test_the_cookie_notice_is_on_the_storefront(): void
    {
        $this->get('/')->assertSee('essential cookies');
    }

    public function test_a_customer_can_download_their_data(): void
    {
        $user = User::factory()->create(['name' => 'Maya']);

        $response = $this->actingAs($user)->get('/profile/export');

        $response->assertOk();
        $json = json_decode($response->streamedContent(), true);
        $this->assertSame($user->email, $json['account']['email']);
        $this->assertArrayHasKey('orders', $json);
    }

    public function test_the_export_requires_login(): void
    {
        $this->get('/profile/export')->assertRedirect('/login');
    }

    public function test_deleting_an_account_keeps_the_orders(): void
    {
        $user = User::factory()->create();
        $order = Order::create(['user_id' => $user->id, 'status' => 'paid', 'total_price' => 20, 'subtotal' => 20, 'paid_at' => now()]);

        $this->actingAs($user)->delete('/profile', ['password' => 'password'])->assertRedirect('/');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $order->refresh();
        $this->assertNull($order->user_id);
        $this->assertSame($user->email, $order->guest_email);
    }

    public function test_backup_copies_a_sqlite_database(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'db').'.sqlite';
        file_put_contents($file, 'data');
        config(['database.default' => 'bk', 'database.connections.bk' => ['driver' => 'sqlite', 'database' => $file]]);

        $this->artisan('db:backup')->assertSuccessful();

        $this->assertNotEmpty(glob(storage_path('app/backups/db-*.sqlite')));
        array_map('unlink', glob(storage_path('app/backups/db-*.sqlite')));
        unlink($file);
    }
}
