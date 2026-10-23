<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slider extends Model
{
    protected $fillable = [
        'name',
        'key',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Common slider keys
    const KEY_HOMEPAGE = 'homepage';
    const KEY_OFFERS = 'offers';
    const KEY_DESTINATIONS = 'destinations';
    const KEY_TOURS = 'tours';

    public static function getCommonKeys(): array
    {
        return [
            self::KEY_HOMEPAGE => 'Homepage',
            self::KEY_OFFERS => 'Offers',
            self::KEY_DESTINATIONS => 'Destinations',
            self::KEY_TOURS => 'Tours',
        ];
    }

    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class)->orderBy('order');
    }

    public function activeSlides(): HasMany
    {
        return $this->slides()->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    public static function findByKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }

    public static function getActiveByKey(string $key): ?self
    {
        return static::active()->byKey($key)->with('activeSlides')->first();
    }
}

