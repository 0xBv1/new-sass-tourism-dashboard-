<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomAdult extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'age_range',
        'price',
        'max_count',
        'description',
        'enabled',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'max_count' => 'integer',
        'enabled' => 'boolean',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
