<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case HOLD = 'hold';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case REFUNDED = 'refunded';
    case REJECTED = 'rejected';

    public static function all(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
