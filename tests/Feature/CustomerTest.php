<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerTest extends TestCase
{

    use refreshDatabase;

    /**
     * Test a Customer can be created with valid input.
     * @return void
     */
    public function test_customer_can_be_registered()
    {
        Storage::fake();
        $this->seed([PermissionSeeder::class , RoleSeeder::class]);

        $user = $this->createAndGetAdmin();

        //Check Valid User can be created and profile image is stored correctly
        $customer = [
            'first_name' => 'First Name' ,
            'last_name' => 'Last Name' ,
            'email' => 'email@e.com' ,
            'password' => 'password5P@' ,
            'password_confirmation' => 'password5P@' ,
            'profile_image' => UploadedFile::fake()->image('profile.jpg') ,
            'address' => 'Mallow Karur' ,
            'phone' => '9876543210' ,
        ];


        $response = $this->actingAs($user)->post(route('customers.store') , $customer);
        $response->assertSessionHasNoErrors();

        $customer['profile_image'] = config('filesystems.profile_images') . '/' . $customer['profile_image']->hashName();
        unset($customer['password_confirmation']);
        unset($customer['password']);
        Storage::disk('public')->assertExists($customer['profile_image']);
        $this->assertDatabaseHas('users' , array_merge($customer , ['status' => 'active']));

        //Check User with missing and invalid fields cannot be created
        $customer = [
            'first_name' => 'First Name' ,
            'email' => 'email1@ee.com' ,
            'password' => 'Password' ,
            'phone' => '1323234'
        ];

        $response = $this->post(route('customers.store') , $customer);
        $response->assertSessionHasErrors(['last_name' , 'phone' , 'profile_image' , 'password']);
        $this->assertDatabaseMissing('users' , $customer);
    }

    /**
     * Test a Customer can be updated with valid input.
     * @return void
     */
    public function test_customer_can_be_updated()
    {
        Storage::fake();
        $this->seed([PermissionSeeder::class , RoleSeeder::class]);
        $user = $this->createAndGetAdmin();

        //Check Valid User can be created and profile image is stored correctly
        $customer = [
            'first_name' => 'First Name' ,
            'last_name' => 'Last Name' ,
            'email' => $user->email ,
            'password' => 'password5P@' ,
            'password_confirmation' => 'password5P@' ,
            'address' => 'Mallow Karur' ,
            'phone' => $user->phone ,
        ];


        $response = $this->actingAs($user)->put(route('customers.update' , $user->id) , $customer);
        $response->assertSessionHasNoErrors();

        unset($customer['password_confirmation']);
        unset($customer['password']);
        Storage::disk('public')->assertExists($user->profile_image);
        $this->assertDatabaseHas('users' , array_merge($customer , ['status' => 'active' , 'id' => $user->id, 'profile_image' => $user->profile_image]));

        //Check User with missing and invalid fields cannot be created
        $customer = [
            'first_name' => 'FFirst Namess' ,
            'email' => 'email1@ee.com' ,
            'password' => 'Password' ,
            'phone' => '1323234'
        ];

        $response = $this->put(route('customers.update' , $user->id) , $customer);
        $response->assertSessionHasErrors(['last_name' , 'phone' , 'password']);
        $this->assertDatabaseMissing('users' , $customer);
    }

    /**
     * Create and return admin user
     * @return User
     */
    function createAndGetAdmin(): User
    {
        return User::factory()->create([
            'profile_image' => UploadedFile::fake()->image('profile.jpg' , 100 , 100)->store('data/profile-images', ['disk' => 'public']),
            'status' => 'active'
        ])->assignRole('admin');
    }
}
