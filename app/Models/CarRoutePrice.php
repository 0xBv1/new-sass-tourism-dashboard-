<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarRoutePrice extends Model
{
    protected $fillable = [
        'car_route_id',
        'car_type',
        'from',
        'to',
        'oneway_price',
        'rounded_price',
    ];


    protected $casts = [
        'from' => 'integer',
        'to' => 'integer',
        'oneway_price' => 'float',
        'rounded_price' => 'float',
    ];

    public function carRoute(): BelongsTo
    {
        return $this->belongsTo(CarRoute::class);
    }
}
