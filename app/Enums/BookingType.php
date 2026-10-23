<?php

namespace App\Enums;

enum BookingType: string
{
    case MIXED = 'mixed';
    case RENTAL_ONLY = 'rental_only';

    public static function all(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
