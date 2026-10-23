<?php

namespace App\Enums;

enum CouponType: string
{
    case FIXED = 'fixed';
    case PERCENTAGE = 'percentage';

    public static function all(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
