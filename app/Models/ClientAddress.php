<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'city',
        'street',
        'building_number',
        'special_mark',
        'special_mark',
        'clients',
        'lat',
        'long',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
