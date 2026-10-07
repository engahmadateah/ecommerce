<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /** GDPR "download my data": everything we hold about the customer as a JSON file. */
    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $user = $request->user();

        $data = [
            'exported_at' => now()->toIso8601String(),
            'account' => $user->only(['name', 'email', 'points', 'level', 'created_at']),
            'orders' => $user->orders()->with('items')->get()->map(fn ($o) => [
                'id' => $o->id,
                'status' => $o->status,
                'total' => $o->total_price,
                'date' => $o->created_at,
                'items' => $o->items->map(fn ($i) => $i->only(['name', 'quantity', 'price']))->all(),
            ])->all(),
            'addresses' => $user->shippingAddresses()->get()->toArray(),
            'reviews' => $user->reviews()->get()->toArray(),
        ];

        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }, 'my-data.json', ['Content-Type' => 'application/json']);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
