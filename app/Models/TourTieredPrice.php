<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourTieredPrice extends Model
{
    protected $fillable = [
        'tour_id',
        'tier_name',
        'min_persons',
        'max_persons',
        'adult_price',
        'child_price',
        'infant_price',
        'benefits',
        'display_order',
        'enabled',
    ];

    protected $casts = [
        'min_persons' => 'integer',
        'max_persons' => 'integer',
        'adult_price' => 'decimal:2',
        'child_price' => 'decimal:2',
        'infant_price' => 'decimal:2',
        'display_order' => 'integer',
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
        return $query->orderBy('display_order');
    }

    public function scopeForPersons($query, int $persons)
    {
        return $query->where('min_persons', '<=', $persons)
                     ->where('max_persons', '>=', $persons);
    }
}


