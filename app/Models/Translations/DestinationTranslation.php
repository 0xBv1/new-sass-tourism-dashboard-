<?php

namespace App\Models\Translations;

use App\Traits\Models\TranslateOnUpdate;
use Illuminate\Database\Eloquent\Model;

class DestinationTranslation extends Model
{
    use TranslateOnUpdate;

    protected $fillable = [
        'title',
        'description',
    ];

    public $timestamps = false;

    function translationFKName(): string
    {
        return 'destination_id';
    }
}
