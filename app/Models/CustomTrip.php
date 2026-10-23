<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

class CustomTrip extends Model
{
    protected $fillable = [
        'destination',
        'assigned_operator_id',
        'assigned_by_id',
        'assigned_at',
        'type',
        'start_date',
        'end_date',
        'month',
        'days',
        'first_name',
        'last_name',
        'nationality',
        'phone_number',
        'email',
        'adults',
        'children',
        'infants',
        'min_person_budget',
        'max_person_budget',
        'flight_offer',
        'additional_notes',
    ];

    protected $casts = [
        'assigned_at' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'days' => 'integer',
        'month' => 'integer',
        'adults' => 'integer',
        'children' => 'integer',
        'infants' => 'integer',
        'min_person_budget' => 'float',
        'max_person_budget' => 'float',
    ];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_operator_id');
    }

    public function assigned_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(CustomizedTripCategory::class, 'custom_trip_categories', 'custom_trip_id', 'category_id');
    }

    public function name(): Attribute
    {
        return new Attribute(
            get: fn() => ucwords($this->first_name . ' ' . $this->last_name)
        );
    }

    public function phone(): Attribute
    {
        return new Attribute(
            get: fn() => trim($this->phone_number)
        );
    }

    public function budget(): Attribute
    {
        return new Attribute(
            get: fn() => $this->min_person_budget . " - " . $this->max_person_budget
        );
    }

    public function typeName(): Attribute
    {
        return new Attribute(
            get: fn() => __('general.custom-trip.types.' . $this->type)
        );
    }

    public function destinationName(): Attribute
    {
        return new Attribute(
            get: fn() => __('general.custom-trip.destinations.' . $this->destination)
        );
    }

    public function monthName(): Attribute
    {
        return new Attribute(
            get: fn() => Carbon::parse(now()->year . '-' . $this->month . '-' . now()->day)->monthName
        );
    }

//    public function joinedCategories(): Attribute
//    {
//        return new Attribute(
//            get: fn() => $this->categories->pluck('title')->implode(', ')
//        );
//    }
}
