<?php

namespace Database\Factories;

use App\Enums\CouponType;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'code' => $this->faker->word(),
            'active' => $this->faker->boolean(),
            'value' => $this->faker->randomFloat(min:1, max: 100),
            'discount_type' => $this->faker->randomElement(CouponType::all()),
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now(),
            'limit_per_usage' => $this->faker->randomNumber(),
            'limit_per_customer' => $this->faker->randomNumber(),
        ];
    }
}
