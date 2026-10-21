<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_register_page_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_user_can_register_new_account(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Staff',
            'email' => 'stafftest@example.com',
            'phone' => '0123456789',
            'role' => 'staff',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'stafftest@example.com',
            'role' => 'staff',
        ]);
    }

    public function test_authenticated_user_can_access_tickets(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/tickets');
        $response->assertStatus(200);
    }
}