<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        $startHour = fake()->numberBetween(9, 17);
        $startMinute = fake()->randomElement([0, 30]);
        $startTime = sprintf('%02d:%02d:00', $startHour, $startMinute);
        $endTime = sprintf('%02d:%02d:00', $startHour + 1, $startMinute);

        return [
            'client_id' => User::factory()->state(['role' => 'client']),
            'specialist_id' => Specialist::factory(),
            'service_id' => Service::factory(),
            'salon_id' => Salon::factory()->active(),
            'appointment_date' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending',
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
