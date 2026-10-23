<?php

namespace App\Models;

use App\Enums\CartItemType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'tour_id',
        'options',
        'adults',
        'children',
        'infants',
        'start_date',
    ];

    protected $casts = [
        'options' => 'array',
        'start_date' => 'date',
    ];

    protected $with = ['tour'];

    protected $appends = ['item_type'];

    public function itemType(): Attribute
    {
        return new Attribute(get: fn() => CartItemType::TOUR->value);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class)->with('seasons');
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function options()
    {
        return !empty($this->options) ? TourOption::whereIn('id', $this->options)->get() : collect();
    }
}
