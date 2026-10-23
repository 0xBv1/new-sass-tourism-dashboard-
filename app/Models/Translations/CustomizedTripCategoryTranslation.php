<?php

namespace App\Models\Translations;

use App\Traits\Models\TranslateOnUpdate;
use Illuminate\Database\Eloquent\Model;

class CustomizedTripCategoryTranslation extends Model
{
    use TranslateOnUpdate;

    protected $fillable = ['title'];

    public $timestamps = false;

    function translationFKName(): string
    {
        return 'customized_trip_category_id';
    }
}
