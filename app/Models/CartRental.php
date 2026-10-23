<?php

namespace App\Models;

use App\Enums\CartItemType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartRental extends Model
{
    protected $fillable = [
        'cart_id',
        'pickup_location_id',
        'destination_id',
        'adults',
        'children',
        'car_route_price',
        'car_type',
        'oneway',
        'pickup_date',
        'pickup_time',
        'return_date',
        'return_time',
        'stops',
    ];

    protected $casts = [
        'stops' => 'array',
        'pickup_date' => 'date',
        'return_date' => 'datetime',
        'return_time' => 'datetime',
    ];

    protected $appends = ['item_type'];

    public function itemType(): Attribute
    {
        return new Attribute(get: fn() => CartItemType::RENTAL->value);
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function pickup(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'pickup_location_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'destination_id');
    }
}
