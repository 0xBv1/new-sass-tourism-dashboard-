<?php

namespace Database\Factories;

use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;

class TourFactory extends Factory
{
    protected $model = Tour::class;

    public function definition(): array
    {
        return [
            'rates' => $this->faker->randomFloat(min: 10, max: 99),
            'reviews_number' => $this->faker->randomNumber(),
            'adult_price' => $this->faker->randomFloat(nbMaxDecimals:1, min: 100, max: 4000),
            'child_price' => $this->faker->randomFloat(nbMaxDecimals:1, min: 10, max: 99),
            'infant_price' => $this->faker->randomFloat(nbMaxDecimals:1, min: 1, max:9),
            'enabled' => $this->faker->boolean(),
            'featured' => $this->faker->boolean(),
            'pricing_groups' => [
                ['from' => 2, 'to' => 3, 'price' => $this->faker->randomFloat(min: 10, max: 500)],
                ['from' => 4, 'to' => 6, 'price' => $this->faker->randomFloat(min: 10, max: 500)],
                ['from' => 7, 'to' => 10, 'price' => $this->faker->randomFloat(min: 10, max: 500)],
            ],
        ];
    }
}
