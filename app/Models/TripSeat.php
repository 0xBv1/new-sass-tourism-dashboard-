<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'seat_number',
        'is_available',
        'booking_id',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    protected $appends = [
        'status_label',
        'status_color'
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(TripBooking::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->is_available ? 'متاح' : 'محجوز';
    }

    public function getStatusColorAttribute(): string
    {
        return $this->is_available ? 'success' : 'danger';
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeBooked($query)
    {
        return $query->where('is_available', false);
    }

    public function scopeByTrip($query, $tripId)
    {
        return $query->where('trip_id', $tripId);
    }

    public function scopeBySeatNumbers($query, array $seatNumbers)
    {
        return $query->whereIn('seat_number', $seatNumbers);
    }

    // Static methods
    public static function generateSeatsForTrip(int $tripId, int $totalSeats): void
    {
        $seats = [];
        
        for ($i = 1; $i <= $totalSeats; $i++) {
            $seats[] = [
                'trip_id' => $tripId,
                'seat_number' => $i,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        self::insert($seats);
    }

    public static function getAvailableSeatsForTrip(int $tripId): \Illuminate\Database\Eloquent\Collection
    {
        return self::byTrip($tripId)->available()->orderBy('seat_number')->get();
    }

    public static function getSeatsStatusForTrip(int $tripId): \Illuminate\Database\Eloquent\Collection
    {
        return self::byTrip($tripId)->orderBy('seat_number')->get();
    }

    public static function bookSeats(int $tripId, array $seatNumbers, int $bookingId): bool
    {
        $seats = self::byTrip($tripId)
            ->bySeatNumbers($seatNumbers)
            ->available()
            ->get();

        if ($seats->count() !== count($seatNumbers)) {
            return false; // Some seats are not available
        }

        return $seats->every(function ($seat) use ($bookingId) {
            return $seat->update([
                'is_available' => false,
                'booking_id' => $bookingId
            ]);
        });
    }

    public static function releaseSeats(int $bookingId): bool
    {
        return self::where('booking_id', $bookingId)->update([
            'is_available' => true,
            'booking_id' => null
        ]);
    }

    public static function getBestAvailableSeats(int $tripId, int $count): array
    {
        $availableSeats = self::getAvailableSeatsForTrip($tripId);
        
        if ($availableSeats->count() < $count) {
            return [];
        }

        // Try to find consecutive seats first
        $seatNumbers = $availableSeats->pluck('seat_number')->toArray();
        $consecutiveSeats = self::findConsecutiveSeats($seatNumbers, $count);
        
        if (!empty($consecutiveSeats)) {
            return $consecutiveSeats;
        }

        // If no consecutive seats, return first available seats
        return array_slice($seatNumbers, 0, $count);
    }

    private static function findConsecutiveSeats(array $seatNumbers, int $count): array
    {
        sort($seatNumbers);
        
        for ($i = 0; $i <= count($seatNumbers) - $count; $i++) {
            $consecutive = true;
            for ($j = 0; $j < $count - 1; $j++) {
                if ($seatNumbers[$i + $j + 1] - $seatNumbers[$i + $j] !== 1) {
                    $consecutive = false;
                    break;
                }
            }
            if ($consecutive) {
                return array_slice($seatNumbers, $i, $count);
            }
        }
        
        return [];
    }
}
