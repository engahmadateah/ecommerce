<?php

namespace App\Services\Checkout;

use App\Exceptions\CheckoutException;
use App\Models\Coupon;
use App\Models\User;
use App\Support\Money;
use Illuminate\Database\Eloquent\Builder;

class CouponService
{
    public function __construct(private readonly LoyaltyService $loyalty)
    {
    }

    public function findByCode(string $code): ?Coupon
    {
        return Coupon::query()->where('code', $code)->first();
    }

    /** Validates the coupon stored in the session. Returns null when there is none. */
    public function resolve(?int $couponId, ?User $user): ?Coupon
    {
        if (! $couponId) {
            return null;
        }

        $coupon = Coupon::query()->find($couponId);

        if (! $coupon) {
            throw CheckoutException::coupon('This coupon is no longer valid.');
        }

        $this->validate($coupon, $user);

        return $coupon;
    }

    /** @throws CheckoutException */
    public function validate(Coupon $coupon, ?User $user): void
    {
        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            throw CheckoutException::coupon('This coupon has expired.');
        }

        // null = unlimited uses. 0 or a positive number is a real cap (0 disables the coupon).
        if ($coupon->usage_limit !== null && $coupon->used >= $coupon->usage_limit) {
            throw CheckoutException::coupon('This coupon has reached its usage limit.');
        }

        if (! $this->loyalty->meetsLevel($user, $coupon->required_level)) {
            throw CheckoutException::coupon('This coupon is not available for your level.');
        }
    }

    public function discountCents(Coupon $coupon, int $subtotalCents): int
    {
        $discount = $coupon->type === 'fixed'
            ? Money::toCents($coupon->value)
            : (int) round($subtotalCents * (float) $coupon->value / 100);

        return max(0, min($discount, $subtotalCents));
    }

    /**
     * Atomically consumes one use. A single conditional UPDATE means two
     * concurrent checkouts can never exceed usage_limit.
     *
     * @throws CheckoutException
     */
    public function redeem(Coupon $coupon): void
    {
        $affected = Coupon::query()
            ->whereKey($coupon->id)
            ->where(function (Builder $q) {
                $q->whereNull('usage_limit')
                    ->orWhereColumn('used', '<', 'usage_limit');
            })
            ->increment('used');

        if ($affected === 0) {
            throw CheckoutException::coupon('This coupon has reached its usage limit.');
        }
    }

    public function release(int $couponId): void
    {
        Coupon::query()->whereKey($couponId)->where('used', '>', 0)->decrement('used');
    }

    /** A coupon worth advertising in the cart for this user, if any. */
    public function suggest(?User $user): ?Coupon
    {
        return Coupon::query()
            ->where(function (Builder $q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('usage_limit')
                    ->orWhereColumn('used', '<', 'usage_limit');
            })
            ->orderBy('id')
            ->get()
            ->first(fn (Coupon $coupon) => $this->loyalty->meetsLevel($user, $coupon->required_level));
    }
}
