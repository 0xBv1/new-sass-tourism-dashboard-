<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Client extends Authenticatable
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'image',
        'name',
        'email',
        'password',
        'phone',
        'nationality',
        'birthdate',
        'blocked',
    ];

    protected $casts = [
        'blocked' => 'boolean',
        'birthdate' => 'date',
    ];

    protected $hidden = ['password'];

    public function addresses(): HasMany
    {
        return $this->hasMany(ClientAddress::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function toursWishlist(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'client_tour_wishlist');
    }
}
