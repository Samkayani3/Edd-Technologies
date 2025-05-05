<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->post('/admin/store-user', [
            'name' => 'Usamaa Bin Hassan',
            'email' => 'usama@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'technician',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'usama@example.com']);
    }
}

