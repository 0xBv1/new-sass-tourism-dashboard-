<?php

namespace App\Models;

use App\Traits\Models\AutoTranslate;
use App\Traits\Models\HasChild;
use App\Traits\Models\HasSeo;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $title
 * @property string $slug
 */
class BlogCategory extends Model
{
    use Translatable, AutoTranslate, HasSeo, HasChild;

    protected $fillable = [
        'parent_id',
        'slug',
        'active',
        'featured_image'
    ];

    public array $translatedAttributes = [
        'title',
    ];

    protected $casts = ['active' => 'boolean'];

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    public function relatedTours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'blog_category_related_tours');
    }
}
