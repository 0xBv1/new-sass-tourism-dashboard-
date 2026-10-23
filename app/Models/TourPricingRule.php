<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourPricingRule extends Model
{
    protected $fillable = [
        'tour_id',
        'name',
        'description',
        'rule_type',
        'conditions',
        'action_type',
        'action_value',
        'priority',
        'stackable',
        'valid_from',
        'valid_until',
        'enabled',
    ];

    protected $casts = [
        'conditions' => 'array',
        'action_value' => 'decimal:2',
        'priority' => 'integer',
        'stackable' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'enabled' => 'boolean',
    ];

    const RULE_TYPES = [
        'date_range' => 'Date Range',
        'day_of_week' => 'Day of Week',
        'month' => 'Month',
        'holiday' => 'Holiday',
        'nationality' => 'Nationality',
        'age_group' => 'Age Group',
        'group_size' => 'Group Size',
        'booking_channel' => 'Booking Channel',
        'promo_code' => 'Promo Code',
        'custom' => 'Custom',
    ];

    const ACTION_TYPES = [
        'percentage' => 'Percentage Discount/Increase',
        'fixed' => 'Fixed Amount Discount/Increase',
        'set_price' => 'Set Fixed Price',
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

    public function scopeOrdered($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('tour_id');
    }

    public function scopeForTour($query, $tourId)
    {
        return $query->where(function ($q) use ($tourId) {
            $q->whereNull('tour_id')->orWhere('tour_id', $tourId);
        });
    }

    public function evaluate(array $context): bool
    {
        if (!$this->conditions) {
            return true;
        }

        foreach ($this->conditions as $field => $condition) {
            if (!$this->evaluateCondition($field, $condition, $context)) {
                return false;
            }
        }

        return true;
    }

    protected function evaluateCondition(string $field, $condition, array $context): bool
    {
        $value = $context[$field] ?? null;

        if (is_array($condition)) {
            if (isset($condition['in'])) {
                return in_array($value, $condition['in']);
            }
            if (isset($condition['not_in'])) {
                return !in_array($value, $condition['not_in']);
            }
            if (isset($condition['min']) && $value < $condition['min']) {
                return false;
            }
            if (isset($condition['max']) && $value > $condition['max']) {
                return false;
            }
            if (isset($condition['equals'])) {
                return $value == $condition['equals'];
            }
        }

        return $value == $condition;
    }

    public function apply(float $price): float
    {
        return match ($this->action_type) {
            'percentage' => $price - ($price * $this->action_value / 100),
            'fixed' => $price - $this->action_value,
            'set_price' => $this->action_value,
            default => $price,
        };
    }
}


