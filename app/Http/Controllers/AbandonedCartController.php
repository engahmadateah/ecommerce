<?php

namespace App\Http\Controllers;

use App\Models\AbandonedCart;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;

/** The two links inside the reminder e-mail. Both are protected by the secret token. */
class AbandonedCartController extends Controller
{
    public function restore(string $token, CartService $cart): RedirectResponse
    {
        abort_unless(strlen($token) === 40, 404);
        $saved = AbandonedCart::query()->where('token', $token)->firstOrFail();

        $cart->restore(collect($saved->items)->pluck('quantity', 'key')->all());

        return redirect()->route('cart.index')->with('success', __('We restored your cart.'));
    }

    public function stop(string $token): RedirectResponse
    {
        abort_unless(strlen($token) === 40, 404);
        AbandonedCart::query()->where('token', $token)->update(['opted_out' => true]);

        return redirect('/')->with('success', __('You will not get cart reminders any more.'));
    }
}
