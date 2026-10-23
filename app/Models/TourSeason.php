<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property Carbon $start_date
 * @property Carbon $end_date
 */
class TourSeason extends Model
{
    use HasFactory;

//    use Enabled;

    protected $fillable = [
        'available',
        'pricing_groups',
        'enabled',
        'title'
    ];

    protected $casts = [
        /*
         * Schema
         * => from
         * => to
         * => adult_price
         * => child_price
         */
        'pricing_groups' => 'collection',
        'enabled' => 'boolean',
        'available' => 'array',
    ];

    protected $hidden = [];

    protected $appends = ['calender_availability'];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function pricingGroups(): Attribute
    {
        return new Attribute(
            get: fn($value) => collect(json_decode($value, true))->map(fn($group) => [
                'from' => (int)$group['from'],
                'to' => (int)$group['to'],
                'price' => (float)$group['price'],
                'child_price' => (float)($group['child_price'] ?? 0),
            ])
        );
    }

    public function calenderAvailability(): Attribute
    {
        $available = $this->available;
        return new Attribute(
            get: function () use ($available) {
                $calender = ['day_numbers' => [], 'day_names' => [], 'month_names' => [], 'years_numbers' => []];
                if (!$available || $available == 'null') {
                    return $calender;
                }
                $calender['day_numbers'] = collect($available['Days'] ?? [])->keys()->map(fn($day) => ((int)$day))->values()->toArray();
                $calender['day_names'] = collect($available['Week'] ?? [])->keys()->map(fn($item) => str($item)->lower())->values()->toArray();
                $calender['month_names'] = collect($available['Month'] ?? [])->keys()->map(fn($item) => str($item)->lower())->values()->toArray();
                $calender['years_numbers'] = collect($available['Year'] ?? [])->keys()->map(fn($year) => (int) $year)->values()->toArray();

                return $calender;
            }
        );
    }
}
