<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_services_index(): void
    {
        Service::factory()->create(['name' => 'Haircut']);

        $response = $this->get('/services');

        $response->assertStatus(200);
        $response->assertSee('Haircut');
    }

    public function test_client_can_view_services_index(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        Service::factory()->create(['name' => 'Manicure']);

        $response = $this->actingAs($client)->get('/services');

        $response->assertStatus(200);
        $response->assertSee('Manicure');
    }

    public function test_services_index_only_shows_active_services(): void
    {
        Service::factory()->create(['name' => 'Active Service', 'is_active' => true]);
        Service::factory()->create(['name' => 'Inactive Service', 'is_active' => false]);

        $response = $this->get('/services');

        $response->assertStatus(200);
        $response->assertSee('Active Service');
        $response->assertDontSee('Inactive Service');
    }

    public function test_guest_can_view_service_detail(): void
    {
        $service = Service::factory()->create(['name' => 'Facial Treatment']);

        $response = $this->get("/services/{$service->id}");

        $response->assertStatus(200);
        $response->assertSee('Facial Treatment');
    }

    public function test_client_can_view_service_detail(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $service = Service::factory()->create(['name' => 'Pedicure']);

        $response = $this->actingAs($client)->get("/services/{$service->id}");

        $response->assertStatus(200);
        $response->assertSee('Pedicure');
    }

    public function test_admin_can_view_admin_services_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Service::factory()->create(['name' => 'Test Service']);

        $response = $this->actingAs($admin)->get('/admin/services');

        $response->assertStatus(200);
        $response->assertSee('Test Service');
    }

    public function test_admin_can_view_create_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/services/create');

        $response->assertStatus(200);
    }

    public function test_admin_can_create_service(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/services', [
            'name' => 'New Service',
            'description' => 'A wonderful service.',
            'duration_minutes' => 60,
            'price' => 49.99,
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('services', ['name' => 'New Service', 'price' => 49.99]);
    }

    public function test_admin_can_view_edit_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = Service::factory()->create();

        $response = $this->actingAs($admin)->get("/admin/services/{$service->id}/edit");

        $response->assertStatus(200);
        $response->assertSee($service->name);
    }

    public function test_admin_can_update_service(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = Service::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($admin)->put("/admin/services/{$service->id}", [
            'name' => 'Updated Name',
            'description' => 'Updated description.',
            'duration_minutes' => 90,
            'price' => 75.00,
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('services', ['id' => $service->id, 'name' => 'Updated Name']);
    }

    public function test_admin_can_deactivate_service(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = Service::factory()->create(['is_active' => true]);

        $response = $this->actingAs($admin)->delete("/admin/services/{$service->id}");

        $response->assertRedirect(route('admin.services.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('services', ['id' => $service->id, 'is_active' => false]);
    }

    public function test_client_cannot_access_admin_services_index(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get('/admin/services');

        $response->assertStatus(403);
    }

    public function test_specialist_cannot_access_admin_services_index(): void
    {
        $specialist = User::factory()->create(['role' => 'specialist']);

        $response = $this->actingAs($specialist)->get('/admin/services');

        $response->assertStatus(403);
    }

    public function test_client_cannot_create_service(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->post('/admin/services', [
            'name' => 'Hacked Service',
            'duration_minutes' => 30,
            'price' => 10.00,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('services', ['name' => 'Hacked Service']);
    }

    public function test_specialist_cannot_update_service(): void
    {
        $specialist = User::factory()->create(['role' => 'specialist']);
        $service = Service::factory()->create(['name' => 'Original']);

        $response = $this->actingAs($specialist)->put("/admin/services/{$service->id}", [
            'name' => 'Tampered',
            'duration_minutes' => 30,
            'price' => 10.00,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('services', ['id' => $service->id, 'name' => 'Original']);
    }

    public function test_guest_cannot_access_admin_services(): void
    {
        $response = $this->get('/admin/services');

        $response->assertRedirect('/login');
    }

    public function test_service_name_is_required(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/services', [
            'name' => '',
            'duration_minutes' => 60,
            'price' => 25.00,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_duration_must_be_at_least_15_minutes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/services', [
            'name' => 'Quick Service',
            'duration_minutes' => 5,
            'price' => 10.00,
        ]);

        $response->assertSessionHasErrors('duration_minutes');
    }

    public function test_duration_must_not_exceed_480_minutes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/services', [
            'name' => 'Long Service',
            'duration_minutes' => 500,
            'price' => 10.00,
        ]);

        $response->assertSessionHasErrors('duration_minutes');
    }

    public function test_price_is_required(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/services', [
            'name' => 'Free Service',
            'duration_minutes' => 30,
        ]);

        $response->assertSessionHasErrors('price');
    }

    public function test_price_must_be_non_negative(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/services', [
            'name' => 'Negative Price',
            'duration_minutes' => 30,
            'price' => -5,
        ]);

        $response->assertSessionHasErrors('price');
    }
}
