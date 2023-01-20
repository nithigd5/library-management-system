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
            'book_id' => Book::factory() ,
            'price' => fn($attr) => Book::find($attr['book_id'])->price ,
            'pending_amount' => fake()->randomElement([0 , $book->price , $book->price / 2]) ,
            'payment_status' => function ($attribute) {
                if ($attribute['pending_amount'] == 0) return 'completed';
                elseif ($attribute['pending_amount'] == $attribute['price']) return 'pending';
                else return 'half-paid';
            } ,
            'for_rent' => fake()->boolean() ,
            'book_issued_at' => fn($attr) => $attr['created_at'] ,
            'purchase_mode' => fake()->randomElement(['online' , 'offline']) ,
            'payment_due' => function ($attr) {
                return $attr['book_issued_at']->copy()->addDays(10);
            } ,
            'book_returned_at' => function ($attr) {
                return fake()->randomElement([$attr['book_issued_at']->copy()->addDays(10) , null]);
            } ,
            'status' => function ($attr) {
                return !is_null($attr['book_returned_at']) ? 'closed' : 'open';
            } ,
        ];
    }
}
