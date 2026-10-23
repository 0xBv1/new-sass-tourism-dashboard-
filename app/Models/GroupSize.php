<?php

namespace App\Models;

use App\Traits\Models\AutoTranslate;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupSize extends Model
{
    use HasFactory, Translatable, SoftDeletes, AutoTranslate;

    public array $translatedAttributes = ['name'];

    protected $fillable = [
        'slug',
        'min_people',
        'max_people',
        'display_order',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'display_order' => 'integer',
        'min_people' => 'integer',
        'max_people' => 'integer',
        'translated_at' => 'datetime',
    ];

    protected $hidden = [
        'translated_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Scope to get only enabled sizes
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

    /**
     * Get the range as a string
     */
    public function getRangeAttribute(): string
    {
        if ($this->min_people && $this->max_people) {
            return "{$this->min_people}-{$this->max_people}";
        }
        return $this->name;
    }
}
