<?php

namespace App\Models\Translations;

use App\Traits\Models\TranslateOnUpdate;
use Illuminate\Database\Eloquent\Model;

class BlogCategoryTranslation extends Model
{
    use TranslateOnUpdate;

    protected $fillable = [
        'title',
    ];

    function translationFKName(): string
    {
        return 'blog_category_id';
    }
}
