<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourPackageItem extends Model
{
    protected $fillable = [
        'tour_package_id',
        'tour_id',
        'quantity',
        'individual_price',
        'display_order',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'individual_price' => 'decimal:2',
        'display_order' => 'integer',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class, 'tour_package_id');
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}


