<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Purchase;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Rented books
        Purchase::factory(10)->create([
            'book_issued_at' => now()->subDays(20) ,
            'created_at' => now()->subDays(20) ,
            'updated_at' => now()->subDays(20) ,
            'for_rent' => true ,
            'book_id' => fake()->randomElement(Book::all('id'))
        ]);

        //Owned books
        Purchase::factory(5)->create([
            'book_issued_at' => now()->subDays(20) ,
            'created_at' => now()->subDays(20) ,
            'updated_at' => now()->subDays(20) ,
            'for_rent' => false ,
            'book_return_due' => null,
            'book_id' => fake()->randomElement(Book::all('id'))
        ]);

        //Rented books and paid
        Purchase::factory(5)->create([
            'book_issued_at' => now()->subDays(20) ,
            'created_at' => now()->subDays(20) ,
            'updated_at' => now()->subDays(20) ,
            'for_rent' => true ,
            'pending_amount'  => 0,
            'book_id' => fake()->randomElement(Book::all('id'))
        ]);

        //Overdue purchases
        Purchase::factory(3)->create([
            'book_issued_at' => now()->subDays(20) ,
            'created_at' => now()->subDays(20) ,
            'updated_at' => now()->subDays(20) ,
            'book_return_due' => now()->subDays(5),
            'book_returned_at' => null,
            'for_rent' => true ,
            'pending_amount'  => 0,
            'book_id' => fake()->randomElement(Book::all('id'))
        ]);

    }
}
