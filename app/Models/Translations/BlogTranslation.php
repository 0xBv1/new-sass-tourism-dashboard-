<?php

namespace App\Models\Translations;

use App\Traits\Models\FixDescriptionImagesLink;
use App\Traits\Models\TranslateOnUpdate;
use Illuminate\Database\Eloquent\Model;

class BlogTranslation extends Model
{
    use FixDescriptionImagesLink, TranslateOnUpdate;

    protected $fillable = [
        'title',
        'description',
        'tags',
    ];

    public $timestamps = false;

    function translationFKName(): string
    {
        return 'blog_id';
    }
}
