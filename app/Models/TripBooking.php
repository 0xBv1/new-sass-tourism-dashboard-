<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class TripBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'client_id',
        'passenger_name',
        'passenger_email',
        'passenger_phone',
        'booking_date',
        'number_of_passengers',
        'selected_seats',
        'total_price',
        'notes',
        'status', // pending, confirmed, cancelled, completed
        'booking_reference',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'number_of_passengers' => 'integer',
        'booking_date' => 'date',
        'selected_seats' => 'array',
    ];

    protected $appends = [
        'status_label',
        'formatted_total_price',
        'price_breakdown',
        'formatted_price_breakdown',
        'status_color',
        'selected_seats_count'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'pending',
            self::STATUS_CONFIRMED => 'confirmed',
            self::STATUS_CANCELLED => 'cancelled',
            self::STATUS_COMPLETED => 'completed'
        ];
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function seats(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TripSeat::class, 'booking_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return number_format($this->total_price, 2);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByTrip($query, $tripId)
    {
        return $query->where('trip_id', $tripId);
    }

    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeByBookingDate($query, $date)
    {
        return $query->whereDate('booking_date', $date);
    }

    public function scopeByBookingDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('booking_date', [$startDate, $endDate]);
    }

    public static function generateBookingReference(): string
    {
        return 'TB-' . strtoupper(uniqid());
    }

    public function calculateTotalPrice(): float
    {
        $trip = $this->trip;
        if (!$trip) {
            return 0;
        }

        // Calculate price based on selected seats count
        $seatCount = $this->selected_seats_count ?: $this->number_of_passengers;
        return $trip->seat_price * $seatCount;
    }

    public function getPriceBreakdownAttribute(): array
    {
        $trip = $this->trip;
        if (!$trip) {
            return [
                'seat_price' => 0,
                'total_price' => 0,
                'seats_count' => $this->selected_seats_count ?: $this->number_of_passengers,
                'selected_seats' => $this->selected_seats ?? [],
            ];
        }

        $seatCount = $this->selected_seats_count ?: $this->number_of_passengers;
        $totalPrice = $trip->seat_price * $seatCount;

        return [
            'seat_price' => $trip->seat_price,
            'total_price' => $this->total_price,
            'seats_count' => $seatCount,
            'selected_seats' => $this->selected_seats ?? [],
        ];
    }

    public function getFormattedPriceBreakdownAttribute(): string
    {
        $breakdown = $this->price_breakdown;
        $seatCount = $breakdown['seats_count'];
        $totalPrice = $breakdown['total_price'];
        
        if (!empty($breakdown['selected_seats'])) {
            $seatsList = implode(', ', $breakdown['selected_seats']);
            return "{$seatCount} مقاعد ({$seatsList}): " . number_format($totalPrice, 2) . " جنيه";
        }
        
        return "{$seatCount} مقاعد: " . number_format($totalPrice, 2) . " جنيه";
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'success',
            'pending' => 'warning',
            'cancelled' => 'danger',
            'completed' => 'info',
            default => 'secondary'
        };
    }

    public function getSelectedSeatsCountAttribute(): int
    {
        return is_array($this->selected_seats) ? count($this->selected_seats) : 0;
    }
}
