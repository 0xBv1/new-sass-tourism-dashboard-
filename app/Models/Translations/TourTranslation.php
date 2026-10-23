<?php

namespace App\Models\Translations;

use App\Traits\Models\FixDescriptionImagesLink;
use App\Traits\Models\TranslateOnUpdate;
use Illuminate\Database\Eloquent\Model;

class TourTranslation extends Model
{
    use FixDescriptionImagesLink;
    use TranslateOnUpdate;

    protected $fillable = [
        'title',
        'overview',
        'highlights',
        'included',
        'excluded',
        'type',
        'run',
        'pickup_time',
    ];

    public $timestamps = false;

    function translationFKName(): string
    {
        return 'tour_id';
    }
}
