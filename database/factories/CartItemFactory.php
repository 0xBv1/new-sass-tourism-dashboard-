<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition()
    {
        return [
            'adults' => $this->faker->randomNumber(),
            'children' => $this->faker->randomNumber(),
            'infants' => $this->faker->randomNumber(),

            'cart_id' => Cart::factory(),
            'tour_id' => Tour::factory(),
        ];
    }
}
