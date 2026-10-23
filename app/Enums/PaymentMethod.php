<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case COD = 'cash';
    case PAYPAL = 'paypal';
    case CARD = 'card';

    public static function all(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
