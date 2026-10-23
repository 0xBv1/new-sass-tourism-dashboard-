<?php

namespace App\Models;

use App\Traits\Models\Enabled;
use Illuminate\Database\Eloquent\Model;

class RedirectRule extends Model
{
    use Enabled;

    public $timestamps = false;

    protected $fillable = [
        'source',
        'destination',
        'enabled',
    ];
}
