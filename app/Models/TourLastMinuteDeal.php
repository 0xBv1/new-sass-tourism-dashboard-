<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourLastMinuteDeal extends Model
{
    protected $fillable = [
        'tour_id',
        'days_before_travel',
        'discount_type',
        'discount_value',
        'min_available_slots',
        'valid_from',
        'valid_until',
        'enabled',
    ];

    protected $casts = [
        'days_before_travel' => 'integer',
        'discount_value' => 'decimal:2',
        'min_available_slots' => 'integer',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'enabled' => 'boolean',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeValid($query)
    {
        $today = Carbon::today();
        return $query->where(function ($q) use ($today) {
            $q->whereNull('valid_from')->orWhere('valid_from', '<=', $today);
        })->where(function ($q) use ($today) {
            $q->whereNull('valid_until')->orWhere('valid_until', '>=', $today);
        });
    }

    public function isApplicable(Carbon $travelDate, ?int $availableSlots = null): bool
    {
        $daysUntilTravel = Carbon::today()->diffInDays($travelDate, false);
        
        if ($daysUntilTravel > $this->days_before_travel || $daysUntilTravel < 0) {
            return false;
        }

        if ($this->min_available_slots && $availableSlots !== null) {
            return $availableSlots >= $this->min_available_slots;
        }

        return true;
    }

    public function apply(float $price): float
    {
        if ($this->discount_type === 'percentage') {
            return $price - ($price * $this->discount_value / 100);
        }
        return $price - $this->discount_value;
    }
}


