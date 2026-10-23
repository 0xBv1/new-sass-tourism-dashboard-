<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class TourPackage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'package_price',
        'original_price',
        'discount_type',
        'discount_value',
        'valid_from',
        'valid_until',
        'max_bookings',
        'current_bookings',
        'enabled',
        'display_order',
    ];

    protected $casts = [
        'package_price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'max_bookings' => 'integer',
        'current_bookings' => 'integer',
        'enabled' => 'boolean',
        'display_order' => 'integer',
    ];

    protected $appends = ['savings', 'savings_percentage', 'is_available'];

    public function items(): HasMany
    {
        return $this->hasMany(TourPackageItem::class)->orderBy('display_order');
    }

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'tour_package_items')
                    ->withPivot(['quantity', 'individual_price', 'display_order']);
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

    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('max_bookings')
              ->orWhereRaw('current_bookings < max_bookings');
        });
    }

    public function getSavingsAttribute(): float
    {
        if (!$this->original_price) {
            return 0;
        }
        return $this->original_price - $this->package_price;
    }

    public function getSavingsPercentageAttribute(): float
    {
        if (!$this->original_price || $this->original_price == 0) {
            return 0;
        }
        return round(($this->savings / $this->original_price) * 100, 1);
    }

    public function getIsAvailableAttribute(): bool
    {
        if ($this->max_bookings && $this->current_bookings >= $this->max_bookings) {
            return false;
        }

        $today = Carbon::today();
        if ($this->valid_from && $this->valid_from > $today) {
            return false;
        }
        if ($this->valid_until && $this->valid_until < $today) {
            return false;
        }

        return $this->enabled;
    }

    public function incrementBookings(): void
    {
        $this->increment('current_bookings');
    }
}


