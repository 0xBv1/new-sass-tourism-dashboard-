<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourDynamicPricing extends Model
{
    protected $table = 'tour_dynamic_pricing';

    protected $fillable = [
        'tour_id',
        'trigger_type',
        'threshold_min',
        'threshold_max',
        'price_action',
        'adjustment_type',
        'adjustment_value',
        'priority',
        'enabled',
    ];

    protected $casts = [
        'threshold_min' => 'integer',
        'threshold_max' => 'integer',
        'adjustment_value' => 'decimal:2',
        'priority' => 'integer',
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

    public function scopeOrdered($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    public function isTriggered(int $value): bool
    {
        if ($this->threshold_max) {
            return $value >= $this->threshold_min && $value <= $this->threshold_max;
        }
        return $value >= $this->threshold_min;
    }

    public function apply(float $price): float
    {
        $adjustment = $this->adjustment_type === 'percentage'
            ? $price * $this->adjustment_value / 100
            : $this->adjustment_value;

        return $this->price_action === 'increase'
            ? $price + $adjustment
            : $price - $adjustment;
    }
}


