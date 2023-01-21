<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
        Storage::fake('public');
        Storage::fake();

        $user = $this->seedAndGetAdmin();

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

        $this->assertDatabaseMissing('users' , ['password' => $customer['password']]);

        Storage::disk('public')->assertExists($customer['profile_image']);

        unset($customer['password']);
        unset($customer['password_confirmation']);
        $this->assertDatabaseHas('users' , array_merge($customer , ['status' => 'active' , 'type' => 'customer']));

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
        Storage::fake('public');
        Storage::fake();

        $user = $this->seedAndGetAdmin();

        //Check Valid User can be created and profile image is stored correctly
        $updatedUser = [
            'first_name' => 'First Name' ,
            'last_name' => 'Last Name' ,
            'email' => $user->email ,
            'address' => 'Mallow Karur' ,
            'phone' => $user->phone ,
            'profile_image' => UploadedFile::fake()->image('profile.jpg')
        ];
        $response = $this->actingAs($user)->put(route('customers.update' , $user->id) , $updatedUser);

        $response->assertSessionHasNoErrors();

        $updatedUser['profile_image'] = config('filesystems.profile_images') . '/' . $updatedUser['profile_image']->hashName();
        Storage::disk('public')->assertExists($updatedUser['profile_image']);

        $this->assertDatabaseHas('users' , array_merge($updatedUser , ['status' => 'active' , 'id' => $user->id ,
            'profile_image' => $updatedUser['profile_image']]));


        //Check User with missing and invalid fields cannot be created
        $updatedUser = [
            'first_name' => 'FFirst Namess' ,
            'email' => 'email1@ee.com' ,
            'phone' => '1323234'
        ];
        $response = $this->put(route('customers.update' , $user->id) , $updatedUser);

        $response->assertSessionHasErrors(['last_name' , 'phone']);

        $this->assertDatabaseMissing('users' , $updatedUser);
    }

    public function test_book_is_deleted()
    {
        $admin = $this->seedAndGetAdmin();
        $user = $this->createAndGetAdmin();

        $response = $this->actingAs($admin)->delete(route('customers.destroy' , $user->id));

        Storage::assertMissing($user->profile_image);
        $this->assertDatabaseMissing('users' , ['id' => $user->id]);

    }
}
