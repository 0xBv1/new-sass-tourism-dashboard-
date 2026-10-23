<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'status' => $this->faker->boolean,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by_id' => User::factory(),
            'en' => [
                'title' => $this->faker->sentence(),
                'content' => $this->faker->paragraph(),
                'slug' => $this->faker->slug,
            ]
        ];
    }
}
