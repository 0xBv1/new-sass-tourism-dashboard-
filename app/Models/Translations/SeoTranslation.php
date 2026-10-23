<?php

namespace App\Models\Translations;

use App\Traits\Models\TranslateOnUpdate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class SeoTranslation extends Model
{
    use TranslateOnUpdate;

    public $timestamps = false;

    protected $table = 'seo_translations';

    protected $fillable =  [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'canonical',
        'twitter_title',
        'twitter_description',
        'structure_schema',
    ];

    public function structureSchema(): Attribute
    {
        return new Attribute(
            get: fn($value) => !empty($value) ? str($value)
                ->replace('https://sunpyramidstours.com/wp-content', asset('storage/media/wordpress-media')) : $value
        );
    }

    function translationFKName(): string
    {
        return 'seo_id';
    }
}
