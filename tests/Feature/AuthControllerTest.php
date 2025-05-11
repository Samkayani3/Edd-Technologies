<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'customer',
        ]);

        $response->assertRedirect('/customers/dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'customer',
        ]);

        $this->assertAuthenticated();
    }

    public function test_registration_fails_with_invalid_data()
    {
        $response = $this->post('/register', [
            'name' => '', 
            'email' => 'not-an-email', 
            'password' => 'short', 
            'password_confirmation' => 'nomatch', 
            'role' => 'unknown', 
        ]);

        $response->assertSessionHasErrors([
            'name', 'email', 'password', 'role'
        ]);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('validpassword'),
            'role' => 'technician',
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'validpassword',
        ]);

        $response->assertRedirect('/technician/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('correctpassword'),
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
