<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HotelRoomBooking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Client Data
        'name',
        'email',
        'phone',
        'nationality',

        // Booking Details
        'hotel_id',
        'room_id',
        'start_date',
        'end_date',
        'guests_count',
        'status',
        'extra_beds_count',
        'extra_beds_total_price',
        'total_price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'blocked' => 'boolean',
        'extra_beds_count' => 'integer',
        'extra_beds_total_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Calculate the number of nights for this booking
     */
    public function getNightsCount(): int
    {
        return Carbon::parse($this->start_date)->diffInDays($this->end_date);
    }

    /**
     * Calculate total price including extra beds
     */
    public function calculateTotalPrice(): float
    {
        $nights = $this->getNightsCount();
        $basePrice = $this->room->night_price * $nights;
        $extraBedsPrice = 0;
        
        if ($this->extra_beds_count > 0 && $this->room->extra_bed_available) {
            $extraBedsPrice = $this->room->extra_bed_price * $this->extra_beds_count * $nights;
        }
        
        return $basePrice + $extraBedsPrice;
    }

    /**
     * Check if extra beds can be added to this booking
     */
    public function canAddExtraBeds(int $extraBedsCount): bool
    {
        return $this->room->canAddExtraBeds($extraBedsCount);
    }

    /**
     * Get total capacity including extra beds
     */
    public function getTotalCapacity(): int
    {
        return $this->room->getTotalCapacity();
    }
}
