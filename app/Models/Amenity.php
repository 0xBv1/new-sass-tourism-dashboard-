<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
*/
class Amenity extends Model
{
    use Translatable;

    protected $fillable = [
        'icon_name',
    ];

    public array $translatedAttributes = [
        'name',
    ];

    protected $hidden = [
        'translations'
    ];
}
