<?php

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bus>
 */
class BusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $maxCapacity = fake()->numberBetween(50, 100);
        return [
            'driver_id' => Driver::factory(),
            'max_capacity' => $maxCapacity,
            'passenger_load' => fake()->numberBetween(0, $maxCapacity),
            'bus_number' => fake()->unique()->numberBetween(100, 999),
        ];
    }
}
