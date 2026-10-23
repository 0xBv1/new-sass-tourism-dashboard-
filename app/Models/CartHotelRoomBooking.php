<?php

namespace App\Models;

use App\Enums\CartItemType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CartHotelRoomBooking extends Model
{
    protected $fillable = [
        'cart_id',
        'hotel_id',
        'room_id',
        'name',
        'email',
        'phone',
        'nationality',
        'start_date',
        'end_date',
        'guests_count',
        'status',
        'extra_beds_count',
        'extra_beds_total_price',
        'total_price',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'guests_count' => 'integer',
        'extra_beds_count' => 'integer',
        'extra_beds_total_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    protected $appends = ['item_type'];

    protected $with = ['hotel', 'room'];

    public function itemType(): Attribute
    {
        return new Attribute(get: fn() => CartItemType::HOTEL_ROOM_BOOKING->value);
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room(): BelongsTo
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