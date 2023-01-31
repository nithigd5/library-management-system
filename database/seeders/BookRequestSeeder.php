<?php

namespace Database\Seeders;

use App\Models\BookRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::all() as $user)
            BookRequest::factory(2)->create([
                'user_id' => $user->id
            ]);
    }
}
