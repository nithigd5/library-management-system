<?php

namespace Tests;

use App\Models\Book;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\UploadedFile;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Create and return admin user
     * @return User
     */
    function createAndGetAdmin($withProfileImage = false): User
    {
        return User::factory()->create([
            'profile_image' => $withProfileImage ? UploadedFile::fake()->image('profile.jpg' , 100 , 100)
                ->store('data/profile-images' , ['disk' => 'public']) : null ,
            'status' => User::STATUS_ACTIVE ,
            'type' => User::TYPE_ADMIN
        ])->assignRole('admin');
    }

    /**
     * Create and return customer user
     * @return User
     */
    function createAndGetCustomer($withProfileImage = false): User
    {
        return User::factory()->create([
            'profile_image' => $withProfileImage ? UploadedFile::fake()->image('profile.jpg' , 100 , 100)
                ->store('data/profile-images' , ['disk' => 'public']) : null ,
            'status' => User::STATUS_ACTIVE ,
            'type' => User::TYPE_CUSTOMER
        ])->assignRole('customer');
    }

    /**
     * Create and return a book
     * @return Book
     */
    function createAndGetBook($is_online = true): Book
    {
        return Book::factory()->create([
            'mode' => $is_online ? 'online' : 'offline',
            'book_path' => $is_online ? UploadedFile::fake()->create('book.pdf' , 100 , 'application/pdf') : null ,
            'image' => UploadedFile::fake()->image('thumbnail.jpg' , 100 , 100)
        ]);
    }

    /**
     *
     * @return User
     */
    public function seedAndGetAdmin($withProfileImage = false): User
    {
        $this->seed([PermissionSeeder::class , RoleSeeder::class]);

        return $this->createAndGetAdmin($withProfileImage);
    }
}
