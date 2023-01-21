<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'), // password
            'remember_token' => Str::random(10),
            'profile_image' => fake()->randomElement(Storage::disk('public')->allFiles('data/profile-images')),
            'last_login' => fake()->dateTimeThisYear,
            'address' => fake()->address(),
            'status' => fake()->randomElement([User::STATUS_ACTIVE, User::STATUS_IN_ACTIVE, User::STATUS_BANNED]),
            'phone' => fake()->regexify('[6-9][0-9]{9}'),
            'type' => fake()->randomElement(['admin', 'customer', 'guest'])
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
