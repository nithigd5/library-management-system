<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RegistrationTest extends TestCase
{

    use refreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();

        $user = Auth::user();
        $this->assertTrue($user->hasRole('user'));

        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
