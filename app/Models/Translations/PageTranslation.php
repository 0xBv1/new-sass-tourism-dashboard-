<?php

namespace App\Models\Translations;

use App\Traits\Models\TranslateOnUpdate;
use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    use TranslateOnUpdate;

    protected $fillable = [
        'title',
        'content',
        'short_description',
    ];

    public $timestamps = false;

    function translationFKName(): string
    {
        return 'page_id';
    }
}
