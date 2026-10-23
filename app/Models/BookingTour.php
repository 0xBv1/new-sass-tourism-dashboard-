<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookingTour extends Pivot
{
    public $timestamps = false;

    protected $table = 'booking_tours';

    protected $fillable = [
        'booking_id',
        'adult_price',
        'child_price',
        'infant_price',
        'tour_id',
        'adults',
        'children',
        'infants',
        'options',
        'start_date',
    ];

    protected $casts = [
        'options' => 'array',
        'adults' => 'integer',
        'children' => 'integer',
        'infants' => 'integer',
        'adult_price' => 'float',
        'child_price' => 'float',
        'infant_price' => 'float',
        'start_date' => 'date',
    ];

    protected $with = ['tour'];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function options(): Collection
    {
        return TourOption::whereIn('id', collect($this->options)->pluck('id')->toArray())->get();
    }
}
