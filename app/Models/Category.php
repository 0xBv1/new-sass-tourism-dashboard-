<?php

namespace App\Models;

use App\Traits\Models\Activated;
use App\Traits\Models\AutoTranslate;
use App\Traits\Models\Enabled;
use App\Traits\Models\HasChild;
use App\Traits\Models\HasSeo;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * @property string $title
 * @property string $description
 * @property string $slug
 */
class Category extends Model
{
    use Translatable, SoftDeletes, Enabled, HasSeo, HasChild, AutoTranslate;

    public array $translatedAttributes = [
        'title',
        'description',
    ];

    protected $fillable = [
        'parent_id',
        'enabled',
        'slug',
        'featured',
        'banner',
        'featured_image',
        'gallery',
        'display_order',
        'tours_count',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'featured' => 'boolean',
        'gallery' => 'array',
        'display_order' => 'integer',
        'tours_count' => 'integer',
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
        return $this->belongsToMany(Tour::class, 'tour_categories');
    }

    public function setToursCount(): void
    {
        $this->forceFill(['tours_count' => $this->tours()->where('enabled', true)->count()])->save();
    }
}
