<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

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
            'book_path' => UploadedFile::fake()->create('book.pdf' , 100 , 'application/pdf')->store('books') ,
            'image' => UploadedFile::fake()->image('thumbnail.jpg' , 100 , 100)->store('data/books/front-covers' , ['disk' => 'public']) ,
            'mode' => fake()->randomElement(['offline' , 'online']) ,
            'is_download_allowed' => function (array $attr) {
                return $attr['mode'] === 'online' && fake()->boolean();
            }
        ];
    }
}
