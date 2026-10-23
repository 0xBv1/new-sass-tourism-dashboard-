<?php

namespace App\Models\Translations;

use App\Traits\Models\TranslateOnUpdate;
use Illuminate\Database\Eloquent\Model;

class LocationTranslation extends Model
{
    use TranslateOnUpdate;

    protected $fillable = ['name'];

    public $timestamps = false;

    function translationFKName(): string
    {
        return 'location_id';
    }
}
