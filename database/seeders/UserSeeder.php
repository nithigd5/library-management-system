<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();

        foreach (User::all() as $user) {
            $user->assignRole('customer');
        }

        User::factory()->create(['email' => 'customer@email.com'])->assignRole('customer');

        User::factory()->create(['email' => 'admin@email.com'])->assignRole('admin');

        User::factory()->create(['email' => 'super_admin@email.com'])->assignRole('super_admin');
    }
}
