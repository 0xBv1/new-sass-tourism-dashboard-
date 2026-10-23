<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slide extends Model
{
    protected $fillable = [
        'slider_id',
        'title',
        'subtitle',
        'description',
        'image',
        'mobile_image',
        'link',
        'button_text',
        'button_style',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    const STYLE_PRIMARY = 'primary';
    const STYLE_SECONDARY = 'secondary';
    const STYLE_OUTLINE = 'outline';

    public static function getButtonStyles(): array
    {
        return [
            self::STYLE_PRIMARY => 'Primary',
            self::STYLE_SECONDARY => 'Secondary',
            self::STYLE_OUTLINE => 'Outline',
        ];
    }

    public function slider(): BelongsTo
    {
        return $this->belongsTo(Slider::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getImageUrlAttribute(): string
    {
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }

    public function getMobileImageUrlAttribute(): ?string
    {
        if (!$this->mobile_image) {
            return null;
        }
        if (filter_var($this->mobile_image, FILTER_VALIDATE_URL)) {
            return $this->mobile_image;
        }
        return asset('storage/' . $this->mobile_image);
    }

    public function hasButton(): bool
    {
        return !empty($this->link) && !empty($this->button_text);
    }
}

