<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Review;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlowBookTest extends TestCase
{
    use RefreshDatabase;

    private function createSalonWithSpecialistAndService(): array
    {
        $owner = User::factory()->create(['role' => 'salon_owner']);
        $salon = Salon::factory()->active()->create(['owner_id' => $owner->id]);
        $service = Service::factory()->create(['salon_id' => $salon->id]);
        $specUser = User::factory()->create(['role' => 'specialist']);
        $specialist = Specialist::factory()->create([
            'user_id' => $specUser->id,
            'salon_id' => $salon->id,
            'is_approved' => true,
        ]);
        $specialist->services()->attach($service->id);

        return compact('owner', 'salon', 'service', 'specialist', 'specUser');
    }

    // --- Public pages ---

    public function test_client_can_view_salons(): void
    {
        $salon = Salon::factory()->active()->create();
        $response = $this->get('/salons');
        $response->assertStatus(200);
        $response->assertSee($salon->name);
    }

    public function test_inactive_salon_not_shown_publicly(): void
    {
        $salon = Salon::factory()->create(['is_active' => false]);
        $response = $this->get('/salons');
        $response->assertDontSee($salon->name);
    }

    public function test_client_can_view_specialist_profile(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $response = $this->get("/salons/{$data['salon']->slug}/specialists/{$data['specialist']->id}");
        $response->assertStatus(200);
        $response->assertSee($data['specUser']->name);
    }

    public function test_client_can_view_service_page(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $response = $this->get("/salons/{$data['salon']->slug}/services/{$data['service']->id}");
        $response->assertStatus(200);
        $response->assertSee($data['service']->name);
    }

    // --- Booking (auto-confirm) ---

    public function test_client_can_book_appointment_auto_confirmed(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $client = User::factory()->create(['role' => 'client']);
        $date = now()->addDays(2)->format('Y-m-d');

        $response = $this->actingAs($client)->post(
            "/book/{$data['salon']->slug}/{$data['specialist']->id}/{$data['service']->id}",
            ['date' => $date, 'time' => '10:00']
        );

        $response->assertRedirect(route('client.appointments.index'));
        $this->assertDatabaseHas('appointments', [
            'client_id' => $client->id,
            'specialist_id' => $data['specialist']->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_double_booking_prevented(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $client = User::factory()->create(['role' => 'client']);
        $date = now()->addDays(3)->format('Y-m-d');
        $url = "/book/{$data['salon']->slug}/{$data['specialist']->id}/{$data['service']->id}";

        $this->actingAs($client)->post($url, ['date' => $date, 'time' => '10:00']);
        $this->assertDatabaseCount('appointments', 1);

        $this->actingAs($client)->post($url, ['date' => $date, 'time' => '10:00']);
        $this->assertDatabaseCount('appointments', 1);
    }

    // --- Client cancellation ---

    public function test_client_can_cancel_own_appointment(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $client = User::factory()->create(['role' => 'client']);

        $appt = Appointment::create([
            'client_id' => $client->id,
            'specialist_id' => $data['specialist']->id,
            'service_id' => $data['service']->id,
            'salon_id' => $data['salon']->id,
            'appointment_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($client)->patch("/client/appointments/{$appt->id}/cancel");
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('appointments', ['id' => $appt->id, 'status' => 'cancelled']);
    }

    public function test_client_cannot_cancel_others_appointment(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $client1 = User::factory()->create(['role' => 'client']);
        $client2 = User::factory()->create(['role' => 'client']);

        $appt = Appointment::create([
            'client_id' => $client1->id,
            'specialist_id' => $data['specialist']->id,
            'service_id' => $data['service']->id,
            'salon_id' => $data['salon']->id,
            'appointment_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($client2)->patch("/client/appointments/{$appt->id}/cancel");
        $response->assertStatus(403);
    }

    // --- Specialist ---

    public function test_specialist_sees_only_own_appointments(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $client = User::factory()->create(['role' => 'client']);

        Appointment::create([
            'client_id' => $client->id,
            'specialist_id' => $data['specialist']->id,
            'service_id' => $data['service']->id,
            'salon_id' => $data['salon']->id,
            'appointment_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($data['specUser'])->get('/specialist/appointments');
        $response->assertStatus(200);
        $response->assertSee($client->name);
    }

    public function test_specialist_can_mark_completed(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $client = User::factory()->create(['role' => 'client']);

        $appt = Appointment::create([
            'client_id' => $client->id,
            'specialist_id' => $data['specialist']->id,
            'service_id' => $data['service']->id,
            'salon_id' => $data['salon']->id,
            'appointment_date' => now()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($data['specUser'])->patch("/specialist/appointments/{$appt->id}/complete");
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('appointments', ['id' => $appt->id, 'status' => 'completed']);
    }

    public function test_specialist_cannot_mark_others_completed(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $otherSpecUser = User::factory()->create(['role' => 'specialist']);
        Specialist::factory()->create(['user_id' => $otherSpecUser->id, 'salon_id' => $data['salon']->id, 'is_approved' => true]);

        $client = User::factory()->create(['role' => 'client']);
        $appt = Appointment::create([
            'client_id' => $client->id,
            'specialist_id' => $data['specialist']->id,
            'service_id' => $data['service']->id,
            'salon_id' => $data['salon']->id,
            'appointment_date' => now()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($otherSpecUser)->patch("/specialist/appointments/{$appt->id}/complete");
        $response->assertStatus(403);
    }

    // --- Specialist approval ---

    public function test_unapproved_specialist_redirected_to_pending(): void
    {
        $specUser = User::factory()->create(['role' => 'specialist']);
        $salon = Salon::factory()->active()->create();
        Specialist::factory()->create([
            'user_id' => $specUser->id,
            'salon_id' => $salon->id,
            'is_approved' => false,
        ]);

        $response = $this->actingAs($specUser)->get('/specialist/dashboard');
        $response->assertRedirect(route('specialist.pending'));
    }

    public function test_unapproved_specialist_can_see_pending_page(): void
    {
        $specUser = User::factory()->create(['role' => 'specialist']);
        $salon = Salon::factory()->active()->create();
        Specialist::factory()->create([
            'user_id' => $specUser->id,
            'salon_id' => $salon->id,
            'is_approved' => false,
        ]);

        $response = $this->actingAs($specUser)->get('/specialist/pending');
        $response->assertStatus(200);
        $response->assertSee('pending approval');
    }

    public function test_salon_owner_can_approve_specialist(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $data['specialist']->update(['is_approved' => false]);

        $response = $this->actingAs($data['owner'])->patch("/salon-owner/specialists/{$data['specialist']->id}/approve");
        $response->assertSessionHas('success');
        $this->assertTrue($data['specialist']->fresh()->is_approved);
    }

    public function test_salon_owner_can_reject_specialist(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $data['specialist']->update(['is_approved' => false]);

        $response = $this->actingAs($data['owner'])->patch("/salon-owner/specialists/{$data['specialist']->id}/reject");
        $response->assertSessionHas('success');
        $this->assertNull($data['specialist']->fresh()->salon_id);
        $this->assertFalse($data['specialist']->fresh()->is_approved);
    }

    public function test_approved_specialist_shown_publicly_unapproved_hidden(): void
    {
        $owner = User::factory()->create(['role' => 'salon_owner']);
        $salon = Salon::factory()->active()->create(['owner_id' => $owner->id]);

        $approvedUser = User::factory()->create(['role' => 'specialist', 'name' => 'Approved Spec']);
        Specialist::factory()->create([
            'user_id' => $approvedUser->id,
            'salon_id' => $salon->id,
            'is_approved' => true,
            'is_active' => true,
        ]);

        $unapprovedUser = User::factory()->create(['role' => 'specialist', 'name' => 'Unapproved Spec']);
        Specialist::factory()->create([
            'user_id' => $unapprovedUser->id,
            'salon_id' => $salon->id,
            'is_approved' => false,
            'is_active' => true,
        ]);

        $response = $this->get("/salons/{$salon->slug}");
        $response->assertStatus(200);
        $response->assertSee('Approved Spec');
        $response->assertDontSee('Unapproved Spec');
    }

    // --- Salon Owner ---

    public function test_salon_owner_crud_services_scoped_to_own_salon(): void
    {
        $data = $this->createSalonWithSpecialistAndService();

        $response = $this->actingAs($data['owner'])->post('/salon-owner/services', [
            'name' => 'New Service',
            'duration_minutes' => 60,
            'price' => 50.00,
        ]);
        $response->assertRedirect(route('salon-owner.services.index'));
        $this->assertDatabaseHas('services', ['name' => 'New Service', 'salon_id' => $data['salon']->id]);
    }

    public function test_salon_owner_cannot_access_another_salons_service(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $otherOwner = User::factory()->create(['role' => 'salon_owner']);
        Salon::factory()->active()->create(['owner_id' => $otherOwner->id]);

        $response = $this->actingAs($otherOwner)->get("/salon-owner/services/{$data['service']->id}/edit");
        $response->assertStatus(403);
    }

    // --- Super Admin ---

    public function test_super_admin_can_approve_salon(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $salon = Salon::factory()->create(['is_active' => false]);

        $response = $this->actingAs($admin)->patch("/super-admin/salons/{$salon->slug}/approve");
        $response->assertSessionHas('success');
        $this->assertTrue($salon->fresh()->is_active);
    }

    public function test_super_admin_can_deactivate_salon(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $salon = Salon::factory()->active()->create();

        $response = $this->actingAs($admin)->patch("/super-admin/salons/{$salon->slug}/deactivate");
        $response->assertSessionHas('success');
        $this->assertFalse($salon->fresh()->is_active);
    }

    // --- Reviews ---

    public function test_review_only_possible_after_completed_appointment(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $client = User::factory()->create(['role' => 'client']);

        $appt = Appointment::create([
            'client_id' => $client->id,
            'specialist_id' => $data['specialist']->id,
            'service_id' => $data['service']->id,
            'salon_id' => $data['salon']->id,
            'appointment_date' => now()->subDay()->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($client)->get("/client/appointments/{$appt->id}/review/create");
        $response->assertStatus(404);
    }

    public function test_client_can_leave_review_on_completed_appointment(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $client = User::factory()->create(['role' => 'client']);

        $appt = Appointment::create([
            'client_id' => $client->id,
            'specialist_id' => $data['specialist']->id,
            'service_id' => $data['service']->id,
            'salon_id' => $data['salon']->id,
            'appointment_date' => now()->subDay()->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($client)->post("/client/appointments/{$appt->id}/review", [
            'rating' => 5,
            'comment' => 'Amazing service!',
        ]);

        $response->assertRedirect(route('client.appointments.index'));
        $this->assertDatabaseHas('reviews', [
            'appointment_id' => $appt->id,
            'rating' => 5,
        ]);
    }

    public function test_review_rating_required_and_validates_1_to_5(): void
    {
        $data = $this->createSalonWithSpecialistAndService();
        $client = User::factory()->create(['role' => 'client']);

        $appt = Appointment::create([
            'client_id' => $client->id,
            'specialist_id' => $data['specialist']->id,
            'service_id' => $data['service']->id,
            'salon_id' => $data['salon']->id,
            'appointment_date' => now()->subDay()->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($client)->post("/client/appointments/{$appt->id}/review", [
            'comment' => 'No rating provided',
        ]);
        $response->assertSessionHasErrors('rating');

        $response = $this->actingAs($client)->post("/client/appointments/{$appt->id}/review", [
            'rating' => 6,
            'comment' => 'Invalid rating',
        ]);
        $response->assertSessionHasErrors('rating');

        $response = $this->actingAs($client)->post("/client/appointments/{$appt->id}/review", [
            'rating' => 0,
            'comment' => 'Invalid rating',
        ]);
        $response->assertSessionHasErrors('rating');
    }
}
