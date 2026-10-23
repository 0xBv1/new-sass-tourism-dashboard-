<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'name' => $this->faker->name(),
            'symbol' => $this->faker->word(),
            'exchange_rate' => $this->faker->randomFloat(min: 1, max: 30),
            'icon' => $this->faker->word(),
            'active' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
