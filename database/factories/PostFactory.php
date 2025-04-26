<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text(60),
            'slug' => str_replace(" ", "-", fake()->unique()->text(20)),
            'body' => fake()->text(200),
            'published_at' => now(),
            'user_id' => User::factory(),
        ];
    }
}
