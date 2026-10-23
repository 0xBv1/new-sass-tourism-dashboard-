<?php

namespace App\Models;

use App\Traits\Models\Activated;
use App\Traits\Models\AutoTranslate;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $question
 * @property string $answer
 */
class Faq extends Model
{
    use SoftDeletes, Translatable, Activated, AutoTranslate;

    protected $fillable = [
        'faq_category_id',
        'active',
        'tag',
        'order',
        'is_active',
    ];

    public array $translatedAttributes = [
        'question',
        'answer',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
        'active',
//        'tag',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
