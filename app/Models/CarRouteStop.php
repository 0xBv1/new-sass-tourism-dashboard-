<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarRouteStop extends Model
{
    protected $fillable = [
        'car_route_id',
        'stop_location_id',
        'price',
    ];

    protected $casts =['price' => 'float'];

    public function carRoute(): BelongsTo
    {
        return $this->belongsTo(CarRoute::class);
    }
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'stop_location_id');
    }
}
