<?php

namespace App\Models\Translations;

use App\Traits\Models\TranslateOnUpdate;
use Illuminate\Database\Eloquent\Model;

class FaqTranslation extends Model
{
    use TranslateOnUpdate;

    protected $table = 'faq_translations';

    protected $fillable = [
        'question',
        'answer',
    ];

    public $timestamps = false;

    function translationFKName(): string
    {
        return 'faq_id';
    }
}
