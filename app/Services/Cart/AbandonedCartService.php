<?php

namespace App\Services\Cart;

use App\Mail\AbandonedCartMail;
use App\Models\AbandonedCart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

/**
 * Remembers what a logged-in customer left in the cart and, once, reminds
 * them by e-mail. The cart itself still lives in the session; this is only
 * a copy so we know what to put in the e-mail and can rebuild it from a link.
 */
class AbandonedCartService
{
    /** Called every time the cart changes. An empty cart removes the copy. */
    public function remember(User $user, array $cart): void
    {
        if ($cart === []) {
            $this->forget($user);

            return;
        }

        $items = collect($cart)->map(fn ($line, $key) => [
            'key' => (string) $key,
            'name' => (string) ($line['name'] ?? ''),
            'price' => (float) ($line['price'] ?? 0),
            'quantity' => (int) ($line['quantity'] ?? 1),
        ])->values()->all();

        $existing = AbandonedCart::query()->where('user_id', $user->id)->first();

        // Same cart as before: don't push the "last activity" time forward, or the reminder never comes.
        if ($existing && $existing->items === $items) {
            return;
        }

        AbandonedCart::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'token' => $existing?->token ?? Str::random(40),
                'items' => $items,
                'last_activity_at' => now(),
                'reminded_at' => null, // a changed cart deserves a fresh reminder
            ],
        );
    }

    public function forget(User $user): void
    {
        AbandonedCart::query()->where('user_id', $user->id)->delete();
    }

    /** Sends the reminders that are due. Returns how many e-mails went out. */
    public function sendDue(): int
    {
        $delay = (int) config('shop.abandoned_cart.delay_hours', 3);
        $maxAge = (int) config('shop.abandoned_cart.max_days', 7);

        $due = AbandonedCart::query()
            ->with('user')
            ->where('opted_out', false)
            ->whereNull('reminded_at')
            ->where('last_activity_at', '<=', now()->subHours($delay))
            ->where('last_activity_at', '>=', now()->subDays($maxAge))
            ->get();

        $sent = 0;

        foreach ($due as $cart) {
            $user = $cart->user;

            if (! $user || blank($user->email) || $cart->items === []) {
                continue;
            }

            // Bought something since leaving the cart? Then no reminder.
            $boughtSince = Order::query()
                ->where('user_id', $user->id)
                ->whereNotNull('paid_at')
                ->where('paid_at', '>=', $cart->last_activity_at)
                ->exists();

            if ($boughtSince) {
                $cart->delete();

                continue;
            }

            try {
                Mail::to($user->email)->send(new AbandonedCartMail($cart));
                $cart->forceFill(['reminded_at' => now()])->save();
                $sent++;
            } catch (Throwable $e) {
                report($e);
            }
        }

        return $sent;
    }
}
