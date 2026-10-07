<?php

namespace App\Services\Checkout;

use App\Models\User;

class LoyaltyService
{
    private const RANKS = ['bronze' => 1, 'silver' => 2, 'gold' => 3];

    public function meetsLevel(?User $user, ?string $requiredLevel): bool
    {
        $userRank = self::RANKS[$user?->level ?? 'bronze'] ?? 1;
        $requiredRank = self::RANKS[$requiredLevel ?? 'bronze'] ?? 1;

        return $userRank >= $requiredRank;
    }

    /**
     * 1 point per full dollar paid. The caller must hold a row lock on the user
     * (see OrderService::confirmPayment) so concurrent payments can't lose points.
     */
    public function award(User $user, int $paidCents): void
    {
        $points = intdiv($paidCents, 100);

        if ($points <= 0) {
            return;
        }

        $user->points = (int) ($user->points ?? 0) + $points;
        $user->save();

        // Level thresholds live in one place: the User model.
        $user->updateLevel();
    }
}
