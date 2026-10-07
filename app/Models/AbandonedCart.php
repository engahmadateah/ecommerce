<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** A snapshot of a logged-in customer's cart, used to send one reminder e-mail. */
class AbandonedCart extends Model
{
    protected $fillable = ['user_id', 'token', 'items', 'last_activity_at', 'reminded_at', 'opted_out'];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'last_activity_at' => 'datetime',
            'reminded_at' => 'datetime',
            'opted_out' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function restoreUrl(): string
    {
        return route('cart.restore', $this->token);
    }

    public function stopUrl(): string
    {
        return route('cart.reminders.stop', $this->token);
    }
}
