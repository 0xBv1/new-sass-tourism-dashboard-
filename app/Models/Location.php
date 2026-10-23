<?php

namespace App\Models;

use App\Traits\Models\Activated;
use App\Traits\Models\AutoTranslate;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $name
 */
class Location extends Model
{
    use  Translatable, SoftDeletes, Activated, AutoTranslate;

    protected $fillable = [
        'active',
    ];

    protected $hidden = [
        'translated_at',
        'deleted_at',
        'active',
        'translations',
        'created_at',
        'updated_at',
    ];

    public array $translatedAttributes = [
        'name',
    ];
}
