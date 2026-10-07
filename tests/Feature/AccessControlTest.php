<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_normal_customer_cannot_open_the_admin_panel(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_an_admin_can_open_the_admin_panel(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get('/admin')
            ->assertOk();
    }

    public function test_policy_pages_are_public(): void
    {
        $this->get('/refund-policy')->assertOk()->assertSee('Refund');
        $this->get('/shipping-policy')->assertOk()->assertSee('Shipping');
    }
}
