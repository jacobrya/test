<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_client_cannot_access_admin_dashboard(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_specialist_cannot_access_admin_dashboard(): void
    {
        $specialist = User::factory()->create(['role' => 'specialist']);

        $response = $this->actingAs($specialist)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_specialist_can_access_specialist_dashboard(): void
    {
        $specialist = User::factory()->create(['role' => 'specialist']);

        $response = $this->actingAs($specialist)->get('/specialist/dashboard');

        $response->assertStatus(200);
    }

    public function test_client_cannot_access_specialist_dashboard(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get('/specialist/dashboard');

        $response->assertStatus(403);
    }

    public function test_admin_cannot_access_specialist_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/specialist/dashboard');

        $response->assertStatus(403);
    }

    public function test_client_can_access_client_dashboard(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get('/client/dashboard');

        $response->assertStatus(200);
    }

    public function test_specialist_cannot_access_client_dashboard(): void
    {
        $specialist = User::factory()->create(['role' => 'specialist']);

        $response = $this->actingAs($specialist)->get('/client/dashboard');

        $response->assertStatus(403);
    }

    public function test_admin_cannot_access_client_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/client/dashboard');

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_any_dashboard(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/specialist/dashboard')->assertRedirect('/login');
        $this->get('/client/dashboard')->assertRedirect('/login');
    }

    public function test_dashboard_redirects_client_to_client_dashboard(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get('/dashboard');

        $response->assertRedirect(route('client.dashboard'));
    }

    public function test_dashboard_redirects_specialist_to_specialist_dashboard(): void
    {
        $specialist = User::factory()->create(['role' => 'specialist']);

        $response = $this->actingAs($specialist)->get('/dashboard');

        $response->assertRedirect(route('specialist.dashboard'));
    }

    public function test_dashboard_redirects_admin_to_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertRedirect(route('admin.dashboard'));
    }
}
