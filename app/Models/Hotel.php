<?php

namespace App\Models;

use App\Traits\Models\HasSeo;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property string $description
 */
class Hotel extends Model
{
    use Translatable, HasSeo;

    public array $translatedAttributes = [
        'name',
        'description'
    ];

    protected $fillable = [
        'stars',
        'enabled',
        'featured_image',
        'banner',
        'gallery',
        'address',
        'map_iframe',
        'slug',
        'phone_contact',
        'whatsapp_contact'
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'gallery' => 'array',
    ];

    protected $hidden = [
        'translations'
    ];

    public function rooms(): Builder|HasMany|Hotel
    {
        return $this->hasMany(Room::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'hotel_amenities');
    }

    public function mealTypes(): HasMany
    {
        return $this->hasMany(MealType::class);
    }
}
