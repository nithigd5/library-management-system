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
        Purchase::factory(10)->create([
            'book_issued_at' => now()->subDays(20) ,
            'created_at' => now()->subDays(20) ,
            'updated_at' => now()->subDays(20) ,
            'for_rent' => true ,
            'book_id' => fake()->randomElement(Book::all('id'))
        ]);

        Purchase::factory(5)->create([
            'book_issued_at' => now()->subDays(20) ,
            'created_at' => now()->subDays(20) ,
            'updated_at' => now()->subDays(20) ,
            'for_rent' => false ,
            'book_id' => fake()->randomElement(Book::all('id'))
        ]);

        Purchase::factory(5)->create([
            'book_issued_at' => now()->subDays(20) ,
            'created_at' => now()->subDays(20) ,
            'updated_at' => now()->subDays(20) ,
            'for_rent' => true ,
            'pending_amount'  => 0,
            'book_id' => fake()->randomElement(Book::all('id'))
        ]);

    }
}
