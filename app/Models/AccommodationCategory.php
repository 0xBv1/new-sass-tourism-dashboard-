<?php

namespace App\Models;

use App\Traits\Models\AutoTranslate;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccommodationCategory extends Model
{
    use HasFactory, Translatable, SoftDeletes, AutoTranslate;

    public array $translatedAttributes = ['name'];

    protected $fillable = [
        'slug',
        'display_order',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'display_order' => 'integer',
        'translated_at' => 'datetime',
    ];

    protected $hidden = [
        'translated_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Scope to get only enabled categories
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
