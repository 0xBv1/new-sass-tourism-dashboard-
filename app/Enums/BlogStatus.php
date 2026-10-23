<?php

namespace App\Enums;

enum BlogStatus: string
{
    case PENDING = 'pending';
    case PUBLISHED = 'published';
    case DRAFTED = 'drafted';

    public static function all(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
