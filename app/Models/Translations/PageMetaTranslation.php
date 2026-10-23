<?php

namespace App\Models\Translations;

use App\Traits\Models\TranslateOnUpdate;
use Illuminate\Database\Eloquent\Model;

class PageMetaTranslation extends Model
{
    use TranslateOnUpdate;

    public $timestamps = false;
    protected $fillable = [
        'meta_value',
    ];

    function translationFKName(): string
    {
        return 'page_meta_id';
    }
}
