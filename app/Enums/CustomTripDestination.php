<?php

namespace App\Enums;

enum CustomTripDestination: string
{
    case EGYPT = 'egypt';
    case OTHER_COUNTRIES = 'other_countries';
    public static function all(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
