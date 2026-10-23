<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Coupon;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'country' => $this->faker->country(),
            'state' => $this->faker->word(),
            'street_address' => $this->faker->address(),
            'status' => $this->faker->randomElement(BookingStatus::all()),
            'payment_method' => $this->faker->randomElement(PaymentMethod::all()),
            'payment_status' => $this->faker->randomElement(PaymentStatus::all()),
            'sub_total_price' => $this->faker->randomFloat(),
            'total_price' => $this->faker->randomFloat(),
            'currency_exchange_rate' => $this->faker->randomFloat(),
            'notes' => $this->faker->word(),
            'meta' => [
                'title' => $this->faker->word(),
            ],
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'coupon_id' => Coupon::factory(),
            'client_id' => Client::factory(),
            'currency_id' => Currency::factory(['active' => true]),
        ];
    }
}
