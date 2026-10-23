<?php

namespace App\Models;

use App\Traits\Models\AutoTranslate;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class CustomizedTripCategory extends Model
{
    use Translatable, AutoTranslate;

    public $timestamps = false;

    public array $translatedAttributes = [
        'title',
    ];
}
