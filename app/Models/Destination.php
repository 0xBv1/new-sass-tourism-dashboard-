<?php

namespace App\Models;

use App\Traits\Models\AutoTranslate;
use App\Traits\Models\Enabled;
use App\Traits\Models\HasChild;
use App\Traits\Models\HasSeo;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $title
 * @property string $description
 * @property string $slug
 */
class Destination extends Model
{
    use Translatable, SoftDeletes, HasSeo, HasChild, AutoTranslate, Enabled;

    public array $translatedAttributes = [
        'title',
        'description',
    ];

    protected $fillable = [
        'parent_id',
        'slug',
        'enabled',
        'global',
        'featured',
        'banner',
        'featured_image',
        'gallery',
        'display_order',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'global' => 'boolean',
        'enabled' => 'boolean',
        'featured' => 'boolean',
        'gallery' => 'array',
        'translated_at' => 'datetime',
    ];

    protected $hidden = [
        'translated_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'tour_destinations');
    }

    public function setToursCount(): void
    {
        $this->forceFill(['tours_count' => $this->tours()->count()])->save();
    }
}
