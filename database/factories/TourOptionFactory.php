<?php

namespace Database\Factories;

use App\Models\TourOption;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TourOptionFactory extends Factory
{
    protected $model = TourOption::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'adult_price' => $this->faker->randomFloat(min: 100, max: 1000),
            'child_price' => $this->faker->randomFloat(min: 100, max: 1000),
            'pricing_groups' => [
                ['from' => 2, 'to' => 3, 'price' => $this->faker->randomFloat(min: 10, max: 500)],
                ['from' => 4, 'to' => 6, 'price' => $this->faker->randomFloat(min: 10, max: 500)],
                ['from' => 7, 'to' => 10, 'price' => $this->faker->randomFloat(min: 10, max: 500)],
            ],
        ];
    }
}
