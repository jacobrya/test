<?php

namespace Database\Factories;

use App\Models\Salon;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Specialist>
 */
class SpecialistFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'specialist']),
            'salon_id' => Salon::factory()->active(),
            'bio' => fake()->paragraph(),
            'photo' => null,
            'experience_years' => fake()->numberBetween(1, 20),
            'is_active' => true,
            'is_approved' => true,
        ];
    }
}
