<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OfflineEntry>
 */
class OfflineEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'entry_at' => fake()->dateTime(),
            'exit_at' => function($attr){
                return fake()->dateTimeBetween($attr['entry_at']);
            }
        ];
    }
}
