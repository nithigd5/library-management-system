<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $book = Book::factory()->create();
        return [
            'user_id' => User::factory() ,
            'book_id' => $book->id ,
            'price' => fn($attr) => $book->price ,
            'pending_amount' => fake()->randomElement([0 , $book->price , $book->price / 2]) ,
            'for_rent' => fake()->boolean() ,
            'book_issued_at' => fn($attr) => $attr['created_at'] ,
            'mode' => fake()->randomElement(['online' , 'offline']) ,
            'payment_due' => function ($attr) {
                return $attr['book_issued_at']->copy()->addDays(10);
            } ,
            'book_return_due' => function ($attr) {
                return $attr['book_issued_at']->copy()->addDays(14);
            },
            'book_returned_at' => function ($attr) {
                return $attr['for_rent'] ? fake()->randomElement([$attr['book_issued_at']->copy()->addDays(10) , null]) : null;
            } ,
            'status' => function ($attr) {
                return !is_null($attr['book_returned_at']) && $attr['pending_amount'] === 0 ? 'closed' : 'open';
            } ,
        ];
    }
}
