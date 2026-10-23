<?php

namespace App\Models;

use App\Traits\Models\AutoTranslate;
use App\Traits\Models\HasSeo;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $title
 * @property string $content
 * @property string $short_description
 */
class Page extends Model
{
    use Translatable, AutoTranslate;

    use HasSeo;

    protected $fillable = [
        'key',
        'banner',
        'gallery',
        'mobile_gallery',
    ];

    public array $translatedAttributes = [
        'title',
        'content',
        'short_description',
    ];
    protected $casts = [
        'gallery' => 'array',
        'mobile_gallery' => 'array',
    ];

    public const MAIN_PAGES = [
        'home',
        'about-us',
        'contact-us',
        'blog',
        'terms-and-conditions',
        'privacy-policy',
        'travel-policy',
        'sun-pyramids-reward-program',
        'why-sun-pyramids',
        'responsibility',
        'career',
        'faqs',
        'car-rental',
        'privacy-and-cookies',
    ];

    public function metas(): HasMany
    {
        return $this->hasMany(PageMeta::class);
    }
}
