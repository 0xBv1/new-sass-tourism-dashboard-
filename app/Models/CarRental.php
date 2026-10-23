<?php

namespace App\Models;

use App\Enums\BookingType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarRental extends Model
{
    protected $fillable = [
        'booking_id',
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
        'name',
        'email',
        'phone',
        'nationality',
        'currency_id',
        'currency_exchange_rate'
    ];

    protected $casts = [
        'car_route_price' => 'float',
        'adults' => 'integer',
        'children' => 'integer',
        'oneway' => 'boolean',
        'pickup_date' => 'datetime',
        'pickup_time' => 'datetime',
        'return_date' => 'datetime',
        'return_time' => 'datetime',
    ];

    protected $appends = ['rental_type'];

    protected $with = [
        'pickup',
        'destination',
        'stops.location'
    ];

    public function pickup(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'pickup_location_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'destination_id');
    }

    public function stops(): HasMany
    {
        return $this->hasMany(CarRentalStop::class)->with('location');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function rentalType(): Attribute
    {
        return new Attribute(
            get: fn() => $this->oneway ? __('validation.attributes.oneway') : __('validation.attributes.rounded')
        );
    }

    public function createBooking(): Booking
    {
        $totalPrice = $this->car_route_price + $this->stops->sum('price');
        $booking =  Booking::create([
            'type' => BookingType::RENTAL_ONLY->value,
            'first_name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'country' => $this->nationality,
            'payment_method' => PaymentMethod::CARD->value,
            'payment_status' => PaymentStatus::PENDING->value,
            'sub_total_price' => $totalPrice,
            'total_price' => $totalPrice,
            'currency_id' => $this->currency_id,
            'currency_exchange_rate' => $this->currency_exchange_rate,
        ]);

        $this->update([
            'booking_id' => $booking->id
        ]);

        return $booking;
    }
}
