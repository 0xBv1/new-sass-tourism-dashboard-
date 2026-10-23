<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailStatus extends Model
{
    public const SPAM = 'spam';
    public const DELIVERABLE = 'deliverable';

    protected $fillable = [
        'email',
        'status',
    ];
}
