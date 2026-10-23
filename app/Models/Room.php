<?php

namespace App\Models;

use App\Traits\Models\Enabled;
use App\Traits\Models\HasSeo;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property string $description
 */
class Room extends Model
{
    use HasSeo, Translatable, Enabled;

    public array $translatedAttributes = [
        'name',
        'description'
    ];

    protected $fillable = [
        'slug',
        'featured_image',
        'banner',
        'hotel_id',
        'gallery',
        'enabled',
        'bed_count',
        'room_type',
        'max_capacity',
        'bed_types',
        'night_price',
    ];

    protected $casts = [
        'gallery' => 'array',
        'enabled' => 'boolean',
    ];

    protected $hidden = [
        'translations'
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'room_amenities');
    }

    public function children(): HasMany
    {
        return $this->hasMany(RoomChild::class);
    }

    public function adults(): HasMany
    {
        return $this->hasMany(RoomAdult::class);
    }
}
