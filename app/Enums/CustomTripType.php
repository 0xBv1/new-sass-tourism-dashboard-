<?php

namespace App\Enums;

enum CustomTripType: string
{
    case EXACT_TIME = 'exact_time';
    case APPROX_TIME = 'approx_time';
    case NOT_SURE = 'not_sure';

    public static function all(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
