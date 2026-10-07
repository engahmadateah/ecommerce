<?php

namespace App\Http\Controllers;

use App\Exceptions\ReturnException;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Services\Returns\ReturnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** A customer asks to return (part of) an order, using the secret order link. */
class OrderReturnController extends Controller
{
    public function store(Request $request, string $token, ReturnService $returns): RedirectResponse
    {
        abort_unless(strlen($token) === 40, 404);
        $order = Order::query()->where('public_token', $token)->firstOrFail();

        $data = $request->validate([
            'reason' => ['required', Rule::in(OrderReturn::REASONS)],
            'details' => ['nullable', 'string', 'max:1000'],
            'quantities' => ['required', 'array'],
            'quantities.*' => ['nullable', 'integer', 'min:0', 'max:1000'],
        ]);

        try {
            $returns->request($order, $data['quantities'], $data['reason'], $data['details'] ?? null);
        } catch (ReturnException $e) {
            return back()->withInput()->withErrors(['return' => $e->getMessage()]);
        }

        return redirect($order->trackingUrl())->with('status', __('Your return request was sent. We will get back to you soon.'));
    }
}
