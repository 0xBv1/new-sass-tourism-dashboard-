<?php

namespace App\Models\Translations;

use App\Traits\Models\FixDescriptionImagesLink;
use App\Traits\Models\TranslateOnUpdate;
use Illuminate\Database\Eloquent\Model;

class TourDayTranslation extends Model
{
    use FixDescriptionImagesLink, TranslateOnUpdate;

    public $timestamps = false;

    protected $fillable = [
        'title', 'description'
    ];

    function translationFKName(): string
    {
        return 'tour_day_id';
    }
}
