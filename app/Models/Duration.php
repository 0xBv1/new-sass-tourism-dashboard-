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
 * @property integer $days
 * @property integer $nights
 * @property string $duration_type
 */
class Duration extends Model
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
        'featured',
        'banner',
        'featured_image',
        'gallery',
        'display_order',
        'days',
        'nights',
        'duration_type',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'enabled' => 'boolean',
        'featured' => 'boolean',
        'gallery' => 'array',
        'translated_at' => 'datetime',
        'days' => 'integer',
        'nights' => 'integer',
        'tours_count' => 'integer',
    ];

    protected $hidden = [
        'translated_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'formatted_duration'
    ];

    // Duration type constants
    const TYPE_DAYS = 'days';
    const TYPE_HOURS = 'hours';
    const TYPE_WEEKS = 'weeks';
    const TYPE_MONTHS = 'months';

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'tour_durations');
    }

    public function setToursCount(): void
    {
        $this->forceFill(['tours_count' => $this->tours()->count()])->save();
    }

    /**
     * Get formatted duration string
     * 
     * @return string
     */
    public function getFormattedDurationAttribute(): string
    {
        if ($this->duration_type === self::TYPE_DAYS && $this->days && $this->nights) {
            return "{$this->days} Days {$this->nights} Nights";
        } elseif ($this->duration_type === self::TYPE_DAYS && $this->days) {
            return "{$this->days} Days";
        } elseif ($this->duration_type === self::TYPE_HOURS && $this->days) {
            return "{$this->days} Hours";
        } elseif ($this->duration_type === self::TYPE_WEEKS && $this->days) {
            return "{$this->days} Weeks";
        } elseif ($this->duration_type === self::TYPE_MONTHS && $this->days) {
            return "{$this->days} Months";
        }

        return $this->title ?? 'N/A';
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'tours_count' => $this->tours()->where('enabled', true)->count(),
            'title' => $this->title,
        ];
    }
} 