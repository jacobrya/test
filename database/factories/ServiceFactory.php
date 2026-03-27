<?php

namespace Database\Factories;

use App\Models\Salon;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $services = [
            'Haircut & Styling', 'Hair Coloring', 'Manicure', 'Pedicure',
            'Facial Treatment', 'Deep Tissue Massage', 'Eyebrow Shaping',
            'Lash Extensions', 'Bridal Makeup', 'Scalp Treatment',
            'Beard Trim', 'Hot Stone Massage', 'Gel Nails', 'Waxing',
            'Aromatherapy', 'Keratin Treatment',
        ];

        return [
            'salon_id' => Salon::factory()->active(),
            'name' => fake()->randomElement($services),
            'description' => fake()->sentence(12),
            'duration_minutes' => fake()->randomElement([30, 45, 60, 90, 120]),
            'price' => fake()->randomFloat(2, 15, 200),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
