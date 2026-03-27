<?php

namespace Tests\Feature\Auth;

use App\Models\Salon;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_super_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $response = $this->actingAs($admin)->get('/super-admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_client_cannot_access_super_admin_dashboard(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $response = $this->actingAs($client)->get('/super-admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_salon_owner_can_access_salon_owner_dashboard(): void
    {
        $owner = User::factory()->create(['role' => 'salon_owner']);
        $response = $this->actingAs($owner)->get('/salon-owner/dashboard');
        $response->assertStatus(200);
    }

    public function test_client_cannot_access_salon_owner_dashboard(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $response = $this->actingAs($client)->get('/salon-owner/dashboard');
        $response->assertStatus(403);
    }

    public function test_specialist_can_access_specialist_dashboard(): void
    {
        $specUser = User::factory()->create(['role' => 'specialist']);
        $salon = Salon::factory()->active()->create();
        Specialist::factory()->create([
            'user_id' => $specUser->id,
            'salon_id' => $salon->id,
            'is_approved' => true,
        ]);
        $response = $this->actingAs($specUser)->get('/specialist/dashboard');
        $response->assertStatus(200);
    }

    public function test_client_cannot_access_specialist_dashboard(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $response = $this->actingAs($client)->get('/specialist/dashboard');
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

    public function test_guest_cannot_access_any_dashboard(): void
    {
        $this->get('/super-admin/dashboard')->assertRedirect('/login');
        $this->get('/salon-owner/dashboard')->assertRedirect('/login');
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

    public function test_dashboard_redirects_super_admin_to_super_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertRedirect(route('super-admin.dashboard'));
    }

    public function test_dashboard_redirects_salon_owner_to_salon_owner_dashboard(): void
    {
        $owner = User::factory()->create(['role' => 'salon_owner']);
        $response = $this->actingAs($owner)->get('/dashboard');
        $response->assertRedirect(route('salon-owner.dashboard'));
    }
}
