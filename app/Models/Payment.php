<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id',
        'booking_id',
        'gateway',
        'transaction_request',
        'transaction_verification',
    ];

    protected $casts = [
        'transaction_request' => 'array',
        'transaction_verification' => 'array',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
