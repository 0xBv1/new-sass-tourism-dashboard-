<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PAID = 'paid';
    case NOT_PAID = 'not_paid';
    case PENDING = 'pending';

    public static function all(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
