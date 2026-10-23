<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourEarlyBirdDiscount extends Model
{
    protected $fillable = [
        'tour_id',
        'days_before_travel',
        'discount_type',
        'discount_value',
        'valid_from',
        'valid_until',
        'enabled',
    ];

    protected $casts = [
        'days_before_travel' => 'integer',
        'discount_value' => 'decimal:2',
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

    public function isApplicable(Carbon $travelDate): bool
    {
        $daysUntilTravel = Carbon::today()->diffInDays($travelDate, false);
        return $daysUntilTravel >= $this->days_before_travel;
    }

    public function apply(float $price): float
    {
        if ($this->discount_type === 'percentage') {
            return $price - ($price * $this->discount_value / 100);
        }
        return $price - $this->discount_value;
    }
}


