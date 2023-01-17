<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->sentence() ,
            'author' => fake()->name() ,
            'price' => fake()->randomFloat(2 , 10 , 5000) ,
            'version' => fake()->numberBetween(1 , 50) ,
            'book_path' => fake()->randomElement(Storage::allFiles('books')) ,
            'image' => fake()->randomElement(Storage::disk('public')->allFiles('data/books/front-covers' , ['disk' => 'public'])) ,
            'mode' => fake()->randomElement(['offline' , 'online']) ,
            'is_download_allowed' => function (array $attr) {
                return $attr['mode'] === 'online' && fake()->boolean();
            }
        ];
    }
}
