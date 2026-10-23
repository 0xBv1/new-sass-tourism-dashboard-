<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourDurationPrice extends Model
{
    protected $fillable = [
        'tour_id',
        'min_days',
        'max_days',
        'price_multiplier',
        'fixed_discount',
        'enabled',
    ];

    protected $casts = [
        'min_days' => 'integer',
        'max_days' => 'integer',
        'price_multiplier' => 'decimal:2',
        'fixed_discount' => 'decimal:2',
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

    public function scopeForDuration($query, int $days)
    {
        return $query->where('min_days', '<=', $days)
                     ->where('max_days', '>=', $days);
    }

    public function apply(float $price): float
    {
        $adjusted = $price * $this->price_multiplier;
        return $adjusted - $this->fixed_discount;
    }
}


