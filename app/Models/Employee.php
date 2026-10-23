<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'position',
        'bio',
        'image',
        'email',
        'phone',
        'social_links',
        'order',
        'is_active',
    ];

    protected $casts = [
        'social_links' => 'array',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }

    public function getFacebookLinkAttribute(): ?string
    {
        return $this->social_links['facebook'] ?? null;
    }

    public function getTwitterLinkAttribute(): ?string
    {
        return $this->social_links['twitter'] ?? null;
    }

    public function getLinkedinLinkAttribute(): ?string
    {
        return $this->social_links['linkedin'] ?? null;
    }

    public function getInstagramLinkAttribute(): ?string
    {
        return $this->social_links['instagram'] ?? null;
    }

    public function hasSocialLinks(): bool
    {
        return !empty(array_filter($this->social_links ?? []));
    }
}

