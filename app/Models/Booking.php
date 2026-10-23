<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Booking extends Model
{
    use LogsActivity, HasFactory;

    protected static array $recordEvents = ['updated', 'deleted'];

    protected $fillable = [
        'type', //mixed,rental_only,tours_only
        'first_name',
        'last_name',
        'phone',
        'email',
        'pickup_location',
        'country',
        'state',
        'street_address',
        'status',
        'payment_method',
        'payment_status',
        'coupon_id',
        'client_id',
        'sub_total_price',
        'total_price',
        'currency_id',
        'currency_exchange_rate',
        'notes',
        'meta',
        'payment_response',
    ];

    protected $casts = [
        'sub_total_price' => 'float',
        'total_price' => 'float',
        'meta' => 'array',
        'payment_response' => 'array',
    ];

    protected $hidden = ['meta', 'payment_response'];

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'booking_tours')
            ->using(BookingTour::class)
            ->withPivot(['start_date', 'adults', 'adult_price', 'child_price', 'children', 'infants',  'infant_price', 'options']);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(CarRental::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName($this->table)
            ->logFillable()
            ->logOnlyDirty();
    }

    public function name(): Attribute
    {
        return new Attribute(
            get: fn() => trim($this->first_name . ' ' . $this->last_name)
        );
    }

    public function isCod(): bool
    {
        return $this->payment_method == PaymentMethod::COD->value;
    }
}
