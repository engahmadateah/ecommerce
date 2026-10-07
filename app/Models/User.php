<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasFactory, Notifiable;

    protected static function booted(): void
    {
        // Deleting an account must not erase the shop's sales records (invoices, taxes, refunds).
        // The orders stay, detached from the account and tied to the customer's e-mail instead.
        static::deleting(function (User $user) {
            Order::query()->where('user_id', $user->id)->update([
                'user_id' => null,
                'guest_email' => $user->email,
            ]);
        });
    }

    /** Only admins may open the Filament panel (/admin). */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'points',
        'level',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function orders()
{
    return $this->hasMany(Order::class);
}
public function wishlist()
{
    return $this->belongsToMany(Product::class, 'wishlists');
}

public function reviews()
{
    return $this->hasMany(Review::class);
}

public function shippingAddresses()
{
    return $this->hasMany(ShippingAddress::class);
}
public function updateLevel()
{
    // اذا انت معطيه مستوى يدوي خاص لا تغيره
    // احذف هاد الشرط اذا بدك الترقية الإجبارية
    if ($this->role === 'admin') {
        return;
    }

    if ($this->points >= 5000) {

        $this->level = 'gold';

    } elseif ($this->points >= 2000) {

        $this->level = 'silver';

    } else {

        $this->level = 'bronze';

    }

    $this->save();
}
}
