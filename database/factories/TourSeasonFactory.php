<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\TourSeason;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TourSeasonFactory extends Factory
{
    protected $model = TourSeason::class;

    public function definition(): array
    {
        return [
            'start_day' => now()->day,
            'start_month' => now()->month,
            'end_day' => now()->addMonth()->day,
            'end_month' => now()->addMonth()->month,
            'pricing_groups' => [
                ['from' => 2, 'to' => 3, 'price' => $this->faker->randomFloat(0, 10, 500)],
                ['from' => 4, 'to' => 6, 'price' => $this->faker->randomFloat(0, 10, 500)],
                ['from' => 7, 'to' => 10, 'price' => $this->faker->randomFloat(0, 10, 500)],
            ],
            'enabled' => $this->faker->boolean(),
            'tour_id' => Tour::factory(),
        ];
    }
}
