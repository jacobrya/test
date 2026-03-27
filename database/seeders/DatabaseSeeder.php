<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Review;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@glowbook.com',
            'role' => 'super_admin',
        ]);

        // Salon Owners + Salons
        $owner1 = User::factory()->create([
            'name' => 'Elena Glow',
            'email' => 'elena@glowbook.com',
            'role' => 'salon_owner',
        ]);
        $salon1 = Salon::create([
            'owner_id' => $owner1->id,
            'name' => 'Glow Beauty Studio',
            'slug' => 'glow-beauty-studio',
            'description' => 'Premium beauty treatments in the heart of the city.',
            'address' => '123 Beauty Ave, Downtown',
            'phone' => '+1-555-0100',
            'is_active' => true,
        ]);

        $owner2 = User::factory()->create([
            'name' => 'Marco Style',
            'email' => 'marco@glowbook.com',
            'role' => 'salon_owner',
        ]);
        $salon2 = Salon::create([
            'owner_id' => $owner2->id,
            'name' => 'Style & Grace',
            'slug' => 'style-and-grace',
            'description' => 'Where style meets elegance. Full-service salon.',
            'address' => '456 Fashion Blvd, Uptown',
            'phone' => '+1-555-0200',
            'is_active' => true,
        ]);

        // Services for Salon 1
        $serviceNames1 = [
            ['name' => 'Haircut & Styling', 'duration_minutes' => 45, 'price' => 35.00],
            ['name' => 'Hair Coloring', 'duration_minutes' => 90, 'price' => 85.00],
            ['name' => 'Manicure', 'duration_minutes' => 30, 'price' => 25.00],
            ['name' => 'Facial Treatment', 'duration_minutes' => 60, 'price' => 55.00],
            ['name' => 'Eyebrow Shaping', 'duration_minutes' => 30, 'price' => 20.00],
            ['name' => 'Lash Extensions', 'duration_minutes' => 90, 'price' => 75.00],
        ];
        $services1 = collect();
        foreach ($serviceNames1 as $s) {
            $services1->push(Service::create(array_merge($s, [
                'salon_id' => $salon1->id,
                'description' => fake()->sentence(10),
            ])));
        }

        // Services for Salon 2
        $serviceNames2 = [
            ['name' => 'Beard Trim', 'duration_minutes' => 30, 'price' => 20.00],
            ['name' => 'Deep Tissue Massage', 'duration_minutes' => 60, 'price' => 70.00],
            ['name' => 'Hot Stone Massage', 'duration_minutes' => 90, 'price' => 95.00],
            ['name' => 'Gel Nails', 'duration_minutes' => 45, 'price' => 40.00],
            ['name' => 'Scalp Treatment', 'duration_minutes' => 45, 'price' => 45.00],
            ['name' => 'Bridal Makeup', 'duration_minutes' => 120, 'price' => 150.00],
        ];
        $services2 = collect();
        foreach ($serviceNames2 as $s) {
            $services2->push(Service::create(array_merge($s, [
                'salon_id' => $salon2->id,
                'description' => fake()->sentence(10),
            ])));
        }

        // Specialists for Salon 1
        $spec1User = User::factory()->create(['name' => 'Anna Styles', 'email' => 'anna@glowbook.com', 'role' => 'specialist']);
        $spec1 = Specialist::create(['user_id' => $spec1User->id, 'salon_id' => $salon1->id, 'bio' => 'Expert stylist with passion for creative cuts.', 'experience_years' => 8, 'is_active' => true, 'is_approved' => true]);
        $spec1->services()->attach($services1->take(3)->pluck('id'));

        $spec2User = User::factory()->create(['name' => 'Lisa Beauty', 'email' => 'lisa@glowbook.com', 'role' => 'specialist']);
        $spec2 = Specialist::create(['user_id' => $spec2User->id, 'salon_id' => $salon1->id, 'bio' => 'Skincare and lash specialist.', 'experience_years' => 5, 'is_active' => true, 'is_approved' => true]);
        $spec2->services()->attach($services1->slice(3)->pluck('id'));

        // Specialists for Salon 2
        $spec3User = User::factory()->create(['name' => 'Jake Barber', 'email' => 'jake@glowbook.com', 'role' => 'specialist']);
        $spec3 = Specialist::create(['user_id' => $spec3User->id, 'salon_id' => $salon2->id, 'bio' => 'Master barber and grooming expert.', 'experience_years' => 12, 'is_active' => true, 'is_approved' => true]);
        $spec3->services()->attach($services2->take(3)->pluck('id'));

        $spec4User = User::factory()->create(['name' => 'Sofia Nails', 'email' => 'sofia@glowbook.com', 'role' => 'specialist']);
        $spec4 = Specialist::create(['user_id' => $spec4User->id, 'salon_id' => $salon2->id, 'bio' => 'Nail art and bridal makeup specialist.', 'experience_years' => 6, 'is_active' => true, 'is_approved' => true]);
        $spec4->services()->attach($services2->slice(3)->pluck('id'));

        // Client users
        $client1 = User::factory()->create(['name' => 'Jane Client', 'email' => 'jane@example.com', 'role' => 'client']);
        $client2 = User::factory()->create(['name' => 'Tom Client', 'email' => 'tom@example.com', 'role' => 'client']);

        // Sample Appointments
        $appointments = [];
        $statuses = ['confirmed', 'confirmed', 'completed', 'completed', 'cancelled', 'confirmed', 'confirmed', 'completed', 'confirmed', 'completed'];
        $dates = [
            now()->addDays(1)->format('Y-m-d'),
            now()->addDays(2)->format('Y-m-d'),
            now()->subDays(5)->format('Y-m-d'),
            now()->subDays(3)->format('Y-m-d'),
            now()->subDays(1)->format('Y-m-d'),
            now()->addDays(3)->format('Y-m-d'),
            now()->addDays(4)->format('Y-m-d'),
            now()->subDays(7)->format('Y-m-d'),
            now()->addDays(5)->format('Y-m-d'),
            now()->subDays(10)->format('Y-m-d'),
        ];

        $specServicePairs = [
            [$spec1, $services1[0], $salon1],
            [$spec1, $services1[1], $salon1],
            [$spec2, $services1[3], $salon1],
            [$spec2, $services1[4], $salon1],
            [$spec3, $services2[0], $salon2],
            [$spec3, $services2[1], $salon2],
            [$spec4, $services2[3], $salon2],
            [$spec1, $services1[2], $salon1],
            [$spec4, $services2[5], $salon2],
            [$spec3, $services2[2], $salon2],
        ];

        for ($i = 0; $i < 10; $i++) {
            [$spec, $service, $salon] = $specServicePairs[$i];
            $startHour = 9 + ($i % 8);
            $endMinutes = $startHour * 60 + $service->duration_minutes;
            $appointments[] = Appointment::create([
                'client_id' => $i % 2 === 0 ? $client1->id : $client2->id,
                'specialist_id' => $spec->id,
                'service_id' => $service->id,
                'salon_id' => $salon->id,
                'appointment_date' => $dates[$i],
                'start_time' => sprintf('%02d:00:00', $startHour),
                'end_time' => sprintf('%02d:%02d:00', intdiv($endMinutes, 60), $endMinutes % 60),
                'status' => $statuses[$i],
            ]);
        }

        // Reviews (only for completed appointments)
        $completedAppointments = collect($appointments)->filter(fn ($a) => $a->status === 'completed');
        $reviewComments = [
            'Absolutely amazing service! Will come back for sure.',
            'Very professional, loved the result.',
            'Good experience overall, friendly staff.',
            'Excellent work, highly recommend!',
            'Great attention to detail.',
        ];

        $reviewIndex = 0;
        foreach ($completedAppointments->take(5) as $appt) {
            Review::create([
                'client_id' => $appt->client_id,
                'specialist_id' => $appt->specialist_id,
                'appointment_id' => $appt->id,
                'rating' => fake()->numberBetween(3, 5),
                'comment' => $reviewComments[$reviewIndex] ?? fake()->sentence(),
            ]);
            $reviewIndex++;
        }
    }
}
