<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkGuestOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_orders_move_to_the_account_after_email_verification(): void
    {
        $mine = Order::create(['guest_email' => 'Maya@Example.com', 'status' => 'paid', 'total_price' => 10, 'subtotal' => 10, 'paid_at' => now()]);
        $other = Order::create(['guest_email' => 'someone@else.com', 'status' => 'paid', 'total_price' => 10, 'subtotal' => 10, 'paid_at' => now()]);
        $user = User::factory()->create(['email' => 'maya@example.com']);

        event(new Verified($user));

        $this->assertSame($user->id, $mine->fresh()->user_id);
        $this->assertNull($mine->fresh()->guest_email);
        $this->assertNull($other->fresh()->user_id);
    }

    public function test_nothing_is_linked_before_verification(): void
    {
        $order = Order::create(['guest_email' => 'maya@example.com', 'status' => 'paid', 'total_price' => 10, 'subtotal' => 10, 'paid_at' => now()]);
        User::factory()->unverified()->create(['email' => 'maya@example.com']);

        $this->assertNull($order->fresh()->user_id);
    }
}
