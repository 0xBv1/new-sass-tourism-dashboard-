<?php

namespace App\Models;

use App\Traits\Models\Enabled;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Trip extends Model
{
    use HasFactory, Enabled;

    protected $fillable = [
        'trip_type', // one_way, round_trip, special_discount
        'trip_name',
        'departure_city_id',
        'arrival_city_id',
        'travel_date',
        'return_date',
        'seat_price',
        'available_seats',
        'departure_time',
        'arrival_time',
        'additional_notes',
        'amenities',
        'enabled',
        'total_seats'
    ];

    protected $casts = [
        'travel_date' => 'date',
        'return_date' => 'date',
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'seat_price' => 'decimal:2',
        'available_seats' => 'integer',
        'total_seats' => 'integer',
        'amenities' => 'array',
        'enabled' => 'boolean',
        'departure_city_id' => 'integer',
        'arrival_city_id' => 'integer',
    ];

    protected $appends = [
        'trip_type_label',
        'formatted_departure_time',
        'formatted_arrival_time',
        'is_available',
        'booked_seats',
        'formatted_price',
        'formatted_total_price',
        'occupancy_rate',
        'occupancy_status',
        'departure_city_name',
        'arrival_city_name'
    ];

    // Trip type constants
    const TYPE_ONE_WAY = 'one_way';
    const TYPE_ROUND_TRIP = 'round_trip';
    const TYPE_SPECIAL_DISCOUNT = 'special_discount';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($trip) {
            $trip->generateTripName();
        });

        static::created(function ($trip) {
            // Generate seats for the trip
            if ($trip->total_seats > 0) {
                TripSeat::generateSeatsForTrip($trip->id, $trip->total_seats);
            }
        });

        static::updating(function ($trip) {
            if ($trip->isDirty(['departure_city_id', 'arrival_city_id', 'trip_type'])) {
                $trip->generateTripName();
            }
            
            // If total_seats changed, regenerate seats
            if ($trip->isDirty('total_seats')) {
                $oldSeats = $trip->getOriginal('total_seats');
                $newSeats = $trip->total_seats;
                
                if ($newSeats > $oldSeats) {
                    // Add more seats
                    for ($i = $oldSeats + 1; $i <= $newSeats; $i++) {
                        TripSeat::create([
                            'trip_id' => $trip->id,
                            'seat_number' => $i,
                            'is_available' => true,
                        ]);
                    }
                } elseif ($newSeats < $oldSeats) {
                    // Remove extra seats (only if they're not booked)
                    $trip->seats()
                        ->where('seat_number', '>', $newSeats)
                        ->where('is_available', true)
                        ->delete();
                }
            }
        });
    }

    public function generateTripName()
    {
        if ($this->departureCity && $this->arrivalCity) {
            $tripName = $this->departureCity->name . ' to ' . $this->arrivalCity->name;
            
            if ($this->trip_type === 'round_trip') {
                $tripName .= ' (Round Trip)';
            } elseif ($this->trip_type === 'special_discount') {
                $tripName .= ' (Special Discount)';
            }
            
            $this->trip_name = $tripName;
        }
    }

    public static function getTripTypes(): array
    {
        return [
            self::TYPE_ONE_WAY => 'One Way',
            self::TYPE_ROUND_TRIP => 'Round Trip',
            self::TYPE_SPECIAL_DISCOUNT => 'Special Discount'
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(TripBooking::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(TripSeat::class);
    }

    public function departureCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'departure_city_id');
    }

    public function arrivalCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'arrival_city_id');
    }

    public function getTripTypeLabelAttribute(): string
    {
        return self::getTripTypes()[$this->trip_type] ?? $this->trip_type;
    }

    public function getFormattedDepartureTimeAttribute(): string
    {
        return $this->departure_time ? $this->departure_time->format('H:i') : '';
    }

    public function getFormattedArrivalTimeAttribute(): string
    {
        return $this->arrival_time ? $this->arrival_time->format('H:i') : '';
    }

    public function getDepartureCityNameAttribute(): string
    {
        return $this->departureCity ? $this->departureCity->name : '';
    }

    public function getArrivalCityNameAttribute(): string
    {
        return $this->arrivalCity ? $this->arrivalCity->name : '';
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->available_seats > 0 && $this->enabled;
    }

    public function getBookedSeatsAttribute(): int
    {
        return $this->total_seats - $this->available_seats;
    }

    public function scopeAvailable($query)
    {
        return $query->where('enabled', true)
                    ->where('available_seats', '>', 0)
                    ->where('travel_date', '>=', now()->toDateString());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('trip_type', $type);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('travel_date', $date);
    }

    public function scopeByCities($query, $from, $to)
    {
        return $query->where('departure_city_id', $from)->where('arrival_city_id', $to);
    }

    public function scopeByDateRange($query, $startDate, $endDate = null)
    {
        if ($endDate) {
            return $query->whereBetween('travel_date', [$startDate, $endDate]);
        }
        return $query->whereDate('travel_date', '>=', $startDate);
    }

    public function hasAvailableSeats(int $passengers): bool
    {
        return $this->available_seats >= $passengers;
    }

    public function hasAvailableSeatsForNumbers(array $seatNumbers): bool
    {
        return $this->seats()
            ->whereIn('seat_number', $seatNumbers)
            ->where('is_available', true)
            ->count() === count($seatNumbers);
    }

    public function getAvailableSeats(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->seats()->available()->orderBy('seat_number')->get();
    }

    public function getAllSeats(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->seats()->orderBy('seat_number')->get();
    }

    public function getBookedSeats(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->seats()->booked()->orderBy('seat_number')->get();
    }

    public function calculateTotalPrice(int $passengers): float
    {
        return $this->seat_price * $passengers;
    }

    public function calculateTotalPriceForSeats(array $seatNumbers): float
    {
        return $this->seat_price * count($seatNumbers);
    }

    public function calculatePriceBreakdown(int $passengers): array
    {
        $passengerPrice = $this->seat_price * $passengers;

        return [
            'passenger_price' => $passengerPrice,
            'total_price' => $passengerPrice,
            'passengers' => $passengers,
            'seat_price' => $this->seat_price,
        ];
    }

    public function calculatePriceBreakdownForSeats(array $seatNumbers): array
    {
        $seatPrice = $this->seat_price * count($seatNumbers);

        return [
            'seat_price' => $this->seat_price,
            'total_price' => $seatPrice,
            'seats_count' => count($seatNumbers),
            'selected_seats' => $seatNumbers,
        ];
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->seat_price, 2) . ' EGP';
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return number_format($this->seat_price * $this->total_seats, 2) . ' EGP';
    }

    public function getOccupancyRateAttribute(): float
    {
        if ($this->total_seats === 0) {
            return 0;
        }
        return round((($this->total_seats - $this->available_seats) / $this->total_seats) * 100, 2);
    }

    public function getOccupancyStatusAttribute(): string
    {
        $rate = $this->occupancy_rate;
        
        if ($rate >= 90) {
            return 'Full';
        } elseif ($rate >= 75) {
            return 'Almost Full';
        } elseif ($rate >= 50) {
            return 'Half Full';
        } elseif ($rate >= 25) {
            return 'Available';
        } else {
            return 'Empty';
        }
    }
}
