<?php

namespace App\Models;

use App\Traits\Models\AutoTranslate;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $name
 * @property string $description
 */
class TourOption extends Model
{
    use HasFactory, Translatable, SoftDeletes, AutoTranslate;

    protected $fillable = [
        'adult_price',
        'pricing_groups',
        'child_price',
    ];

    public array $translatedAttributes = [
        'name',
        'description',
    ];

    protected $casts = [
        'translated_at' => 'datetime',
        'adult_price' => 'float',
        'child_price' => 'float',
        'pricing_groups' => 'collection',
    ];

    protected $hidden = [
        'translated_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'tour_option_tours');
    }

    public function pricingGroups(): Attribute
    {
        return new Attribute(
            get: fn($value) => collect(json_decode($value, true))->map(fn($group) => [
                'from' => (int)$group['from'],
                'to' => (int)$group['to'],
                'price' => (float)$group['price'],
                'child_price' => (float)($group['child_price'] ?? 0),
            ])
        );
    }


    public function calcAdultPrice($adults)
    {
        $group = $this->pricing_groups
            ->filter(fn($group) => $adults >= $group['from'] && $adults <= $group['to'])
            ->first();

        return $group['price'] ?? $this->adult_price;
    }

    public function calcChildPrice($adults)
    {
        $group = $this->pricing_groups
            ->filter(fn($group) => $adults >= $group['from'] && $adults <= $group['to'])
            ->first();

        return $group['child_price'] ?? $this->child_price;
    }
}
