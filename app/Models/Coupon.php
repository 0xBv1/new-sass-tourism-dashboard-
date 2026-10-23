<?php

namespace App\Models;

use App\Enums\CouponType;
use App\Exceptions\CouponNotAvailableForSelectedTourCategoriesException;
use App\Exceptions\CouponNotAvailableForSelectedToursException;
use App\Exceptions\ExpiredCouponException;
use App\Exceptions\InActiveCouponException;
use App\Exceptions\LoginFirstToUseCouponException;
use App\Exceptions\UsedCouponException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Throwable;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code',
        'active',
        'value',
        'discount_type',
        'start_date',
        'end_date',
        'limit_per_usage',
        'limit_per_customer',
    ];

    protected $casts = [
        'active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * @throws Throwable
     */
    public function validate(): bool
    {
        throw_if(!$this->active, new InActiveCouponException);
        throw_if($this->expired(), new ExpiredCouponException);
        throw_if($this->limit_per_usage && Booking::whereCouponId($this->id)->count() >= $this->limit_per_usage, new UsedCouponException);
        throw_if($this->limit_per_customer && !auth('client')->check(), new LoginFirstToUseCouponException);
        $authed_client_coupon_usage = Booking::whereCouponId($this->id)->whereClientId(auth('client')->id())->count();
        throw_if($this->limit_per_customer && $authed_client_coupon_usage >= $this->limit_per_customer, new UsedCouponException);

        $cart_service = new \App\Services\Client\Cart();
        $cart_service->load();
        $tours = $cart_service->items->filter(fn($item) => $item instanceof CartItem)->pluck('tour_id')->toArray();
        if ($this->tours()->exists()) {
            $this->validateCouponWithToursInCart($tours);
        } else {
            $this->validateCouponWithTourCategoriesInCart($tours);
        }
        return true;
    }

    public function expired(): bool
    {
        return $this->start_date && $this->end_date && !now()->between($this->start_date, $this->end_date);
    }

    /**
     * @throws Throwable
     */
    public function validateCouponWithTourCategoriesInCart($tours): self
    {
        $cart_categories = DB::table('tour_categories')
            ->whereIn('tour_id', $tours)
            ->pluck('category_id')
            ->toArray();
        $coupon_categories = $this->categories()->pluck('categories.id')->toArray();
        throw_if(array_diff($cart_categories, $coupon_categories), new CouponNotAvailableForSelectedTourCategoriesException);
        return $this;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'coupon_categories');
    }

    /**
     * @throws Throwable
     */
    public function validateCouponWithToursInCart($tours): self
    {

        $coupon_tours = $this->tours()->pluck('tours.id')->toArray();
        throw_if(array_diff($tours, $coupon_tours), new CouponNotAvailableForSelectedToursException);
        return $this;
    }

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'tours_coupon');
    }

    public function apply($amount)
    {
        return match ($this->discount_type) {
            CouponType::FIXED->value => $amount - $this->value,
            CouponType::PERCENTAGE->value => $amount - ($this->value / 100 * $amount),
        };
    }
}
