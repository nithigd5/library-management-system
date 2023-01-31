<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookRequest>
 */
class BookRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'book_name' => fake()->sentence(),
            'book_author' => fake()->name(),
            'user_id' => User::factory(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['pending', 'accepted', 'rejected']),
            'comment' => fake()->paragraph()
        ];
    }
}
