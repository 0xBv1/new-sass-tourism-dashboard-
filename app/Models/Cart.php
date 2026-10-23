<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'ip',
    ];

    protected $with = [
        'items'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
    public function rentals(): HasMany
    {
        return $this->hasMany(CartRental::class);
    }

    public static function guest(): ?Cart
    {
        return self::where('ip' , request()->ip())->firstOrCreate([
            'ip' =>  request()->ip()
        ]);
    }
}
