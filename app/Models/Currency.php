<?php

namespace App\Models;

use App\Traits\Models\Activated;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use Activated, HasFactory;

    protected $fillable = [
        'active',
        'title',
        'name',
        'symbol',
        'exchange_rate',
        'default',
        'icon',
    ];

    protected $casts = [
        'default' => 'boolean'
    ];

    public function name(): Attribute
    {
        return new Attribute(
            get: fn($value) => strtoupper($value),
            set: fn($value) => strtoupper($value),
        );
    }
}
