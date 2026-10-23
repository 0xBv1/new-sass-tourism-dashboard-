<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'nickname',
        'name',
        'email',
        'phone',
        'country_phone_code',
        'adults',
        'children',
        'infants',
        'arrival_date',
        'departure_date',
        'notes',
        'nationality',
        'hotel_choice',
        'age_range',
        'hear_about_us',
        'cruise_type',
        'cruise_pick_drop_off',
        'cruise_duration',
        'type',
        'status',
        'assigned_to',
    ];

    protected $casts = [
        'arrival_date' => 'date',
        'departure_date' => 'date',
        'adults' => 'integer',
        'children' => 'integer',
        'infants' => 'integer',
    ];

    const TYPE_APPOINTMENT = 'appointment';
    const TYPE_NILE_CRUISE = 'nile_cruise';
    const TYPE_PLAN_TRIP = 'plan_trip';

    const STATUS_PENDING = 'pending';
    const STATUS_CONTACTED = 'contacted';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';

    public static function getTypes(): array
    {
        return [
            self::TYPE_APPOINTMENT => 'Appointment',
            self::TYPE_NILE_CRUISE => 'Nile Cruise',
            self::TYPE_PLAN_TRIP => 'Plan Trip',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONTACTED => 'Contacted',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getTotalGuestsAttribute(): int
    {
        return $this->adults + $this->children + $this->infants;
    }

    public function getDurationInDaysAttribute(): int
    {
        return $this->arrival_date->diffInDays($this->departure_date);
    }

    public function getFormattedPhoneAttribute(): string
    {
        if ($this->country_phone_code) {
            return "({$this->country_phone_code}) {$this->phone}";
        }
        return $this->phone;
    }
}

