<?php

namespace App\Models\Translations;

use App\Traits\Models\TranslateOnUpdate;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    use TranslateOnUpdate;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
    ];

    function translationFKName(): string
    {
        return 'category_id';
    }
}
