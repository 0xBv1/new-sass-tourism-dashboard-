<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourDatePrice extends Model
{
    protected $fillable = [
        'tour_id',
        'date',
        'adult_price',
        'child_price',
        'infant_price',
        'label',
        'enabled',
    ];

    protected $casts = [
        'date' => 'date',
        'adult_price' => 'decimal:2',
        'child_price' => 'decimal:2',
        'infant_price' => 'decimal:2',
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

    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }
}


