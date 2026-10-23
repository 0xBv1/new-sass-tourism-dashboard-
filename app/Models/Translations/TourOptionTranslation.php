<?php

namespace App\Models\Translations;

use App\Traits\Models\TranslateOnUpdate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourOptionTranslation extends Model
{
    use TranslateOnUpdate;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description'
    ];

    function translationFKName(): string
    {
        return 'tour_option_id';
    }
}
