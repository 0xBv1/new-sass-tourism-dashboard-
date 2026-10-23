<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarRentalStop extends Model
{
    protected $fillable = [
        'car_rental_id',
        'stop_location_id',
        'price',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'stop_location_id');
    }
    public function carRental(): BelongsTo
    {
        return $this->belongsTo(CarRental::class, 'car_rental_id');
    }
}
