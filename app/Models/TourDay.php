<?php

namespace App\Models;

use App\Traits\Models\AutoTranslate;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourDay extends Model
{
    use Translatable, AutoTranslate;

    public array $translatedAttributes = [
        'title',
        'description'
    ];

    protected $casts = [
        'translated_at' => 'datetime',
    ];

    protected $hidden = [
        'translated_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
