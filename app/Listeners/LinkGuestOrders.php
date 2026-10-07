<?php

namespace App\Listeners;

use App\Models\Order;
use Illuminate\Auth\Events\Verified;

/**
 * When someone confirms their e-mail, orders they placed earlier as a guest
 * with that same address show up in their account.
 *
 * Only after verification: otherwise anyone could register with a stranger's
 * e-mail and read that person's orders.
 */
class LinkGuestOrders
{
    public function handle(Verified $event): void
    {
        $user = $event->user;

        if (! $user instanceof \App\Models\User || blank($user->email)) {
            return;
        }

        Order::query()
            ->whereNull('user_id')
            ->whereRaw('LOWER(guest_email) = ?', [mb_strtolower($user->email)])
            ->update(['user_id' => $user->id, 'guest_email' => null]);
    }
}
