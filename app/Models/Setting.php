<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'option_key',
        'option_value',
    ];

    protected $casts = [
        'option_value' => 'json'
    ];

    public function scopeKey($query, $key)
    {
        return $query->whereOptionKey($key);
    }
}
