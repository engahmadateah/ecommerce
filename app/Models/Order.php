<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use \App\Models\Concerns\LogsActivity;

    protected $fillable = [
        'user_id',
        'guest_email',
        'total_price',
        'subtotal',
        'discount_total',
        'shipping_total',
        'tax_total',
        'tax_included',
        'refunded_total',
        'coupon_id',
        'status',
        'stripe_session_id',
        'payment_intent_id',
        'paid_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'tax_included' => 'boolean',
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->public_token ??= \Illuminate\Support\Str::random(40);
        });

        // Tell the customer when an admin moves the order to "shipped".
        static::updated(function (Order $order) {
            if ($order->wasChanged('status') && $order->status === 'shipped') {
                app(\App\Services\Notifications\OrderNotifier::class)->shipped($order);
            }
        });
    }

    /** Who gets the e-mails: the account e-mail, or the address typed by a guest. */
    public function customerEmail(): ?string
    {
        return $this->user?->email ?? $this->guest_email;
    }

    public function customerName(): string
    {
        return $this->user?->name ?? $this->shipping?->full_name ?? 'Customer';
    }

    /** Secret link to follow this order without an account. */
    public function trackingUrl(): string
    {
        if (blank($this->public_token)) {
            $this->forceFill(['public_token' => \Illuminate\Support\Str::random(40)])->save();
        }

        return route('orders.track', $this->public_token);
    }

    public function invoiceNumber(): string
    {
        return 'INV-'.str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    public function isGuest(): bool
    {
        return $this->user_id === null;
    }

    /** Logged-in owner, or the guest browser session that placed it. */
    public function isOwnedBy(?\App\Models\User $user, array $guestOrderIds): bool
    {
        if ($user) {
            return $this->user_id === $user->id;
        }

        return $this->user_id === null && in_array($this->id, $guestOrderIds, true);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(OrderReturn::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function shippingAddress()
{
    return $this->belongsTo(ShippingAddress::class);
}
public function shipping()
{
    return $this->hasOne(Shipping::class);
}
}
