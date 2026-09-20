<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'birthdate',
    'city',
    'avatar',
    'role', // tambahin ini
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    // Hanya user dengan role admin yang bisa akses Filament panel
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }
        public function addresses()
    {
        return $this->hasMany(Address::class);
    }
        public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
        public function orders()
    {
        return $this->hasMany(Order::class);
    }
        public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
