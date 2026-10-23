<?php

namespace App\Models;

use App\Traits\Models\AutoTranslate;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $og_title
 * @property string $og_description
 * @property string $viewport
 * @property string $robots
 * @property string $canonical
 * @property string $twitter_title
 * @property string $twitter_description
 * @property string $structure_schema
 */
class Seo extends Model
{
    use Translatable, AutoTranslate;

    protected $table = 'seos';

    protected $fillable = [
        'og_image',
        'og_type',
        'viewport',
        'robots',
        'twitter_card',
        'twitter_creator',
        'twitter_image',
    ];

    public array $translatedAttributes = [
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

    public array $excludedFromAutoTranslate = ['structure_schema'];

    protected $hidden = [
        'id',
        'seo_type',
        'seo_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the parent seoable model (tour or post...etc.).
     */
    public function seo(): MorphTo
    {
        return $this->morphTo();
    }
}
