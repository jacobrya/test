<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_client_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Client',
            'email' => 'client@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'client',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('client.dashboard', absolute: false));
    }

    public function test_new_specialist_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Specialist',
            'email' => 'specialist@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'specialist',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('specialist.dashboard', absolute: false));
    }

    public function test_admin_role_cannot_be_selected_during_registration(): void
    {
        $response = $this->post('/register', [
            'name' => 'Sneaky Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('role');
    }

    public function test_role_is_required_during_registration(): void
    {
        $response = $this->post('/register', [
            'name' => 'No Role User',
            'email' => 'norole@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('role');
    }
}
