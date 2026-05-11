<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
