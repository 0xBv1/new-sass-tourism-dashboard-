<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class AccommodationCategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
