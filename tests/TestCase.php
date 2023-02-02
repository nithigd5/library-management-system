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
     * @param bool $withProfileImage
     * @return User
     */
    function createAndGetCustomer(bool $withProfileImage = false): User
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
     * @param bool $is_online
     * @return Book
     */
    function createAndGetBook(bool $is_online = true): Book
    {
        return Book::factory()->create([
            'mode' => $is_online ? 'online' : 'offline',
            'book_path' => $is_online ? UploadedFile::fake()->create('book.pdf' , 100 , 'application/pdf') : null ,
            'image' => UploadedFile::fake()->image('thumbnail.jpg' , 100 , 100)
        ]);
    }

    /**
     *
     * @param bool $withProfileImage
     * @return User
     */
    public function seedAndGetAdmin(bool $withProfileImage = false): User
    {
        $this->seedRolesPermissions();

        return $this->createAndGetAdmin($withProfileImage);
    }

    /**
     * @return void
     */
    public function seedRolesPermissions(): void
    {
        $this->seed([PermissionSeeder::class , RoleSeeder::class]);
    }
}
