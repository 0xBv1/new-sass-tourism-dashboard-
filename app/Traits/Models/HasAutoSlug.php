<?php

namespace App\Traits\Models;

use Illuminate\Support\Str;

trait HasAutoSlug
{
    /**
     * Boot the trait and add the model events.
     */
    protected static function bootHasAutoSlug()
    {
        static::creating(function ($model) {
            $model->generateSlugIfEmpty();
        });

        static::updating(function ($model) {
            $model->generateSlugIfEmpty();
        });
    }

    /**
     * Generate slug if it's empty or if the title has changed.
     */
    protected function generateSlugIfEmpty()
    {
        // If slug is already set and not empty, don't override it
        if (!empty($this->slug)) {
            return;
        }

        // Get the title from the main model or from translations
        $title = $this->getTitleForSlug();
        
        if (!empty($title)) {
            $this->slug = $this->generateUniqueSlug($title);
        }
    }

    /**
     * Get the title to use for slug generation.
     */
    protected function getTitleForSlug(): string
    {
        // First try to get title from the main model
        if (isset($this->title) && !empty($this->title)) {
            return $this->title;
        }

        // If using translatable, get title from default locale
        if (method_exists($this, 'translate') && isset($this->translatedAttributes)) {
            $defaultLocale = config('app.locale');
            $translation = $this->translate($defaultLocale);
            if ($translation && isset($translation->title) && !empty($translation->title)) {
                return $translation->title;
            }
        }

        // If still no title, try to get from any available translation
        if (method_exists($this, 'translations')) {
            $translation = $this->translations()->whereNotNull('title')->where('title', '!=', '')->first();
            if ($translation) {
                return $translation->title;
            }
        }

        return '';
    }

    /**
     * Generate a unique slug from the given text.
     */
    protected function generateUniqueSlug(string $text): string
    {
        $baseSlug = Str::slug($text);
        $slug = $baseSlug;
        $counter = 1;

        // Check if slug already exists and make it unique
        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Set the slug attribute manually.
     */
    public function setSlugAttribute($value)
    {
        if (empty($value)) {
            // If empty, let the automatic generation handle it
            return;
        }
        
        $this->attributes['slug'] = Str::slug($value);
    }
}
