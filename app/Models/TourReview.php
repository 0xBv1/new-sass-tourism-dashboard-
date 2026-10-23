<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourReview extends Model
{
    protected $fillable = [
        'rate',
        'content',
        'tour_id',
        'reviewer_name',
    ];

    protected $casts = [
        'rate' => 'float'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
