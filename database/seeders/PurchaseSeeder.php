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
            'book_issued_at' => now()->subMonth() ,
            'created_at' => now()->subMonth() ,
            'updated_at' => now()->subMonth() ,
            'for_rent' => true ,
            'book_id' => fake()->randomElement(Book::all('id'))
        ]);

        Purchase::factory(5)->create([
            'book_issued_at' => now()->subMonth() ,
            'created_at' => now()->subMonth() ,
            'updated_at' => now()->subMonth() ,
            'for_rent' => false ,
            'book_id' => fake()->randomElement(Book::all('id'))
        ]);
    }
}
