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
        return [
            'driver_id' => Driver::factory(),
            'seats' => json_encode($this->generateSeats()),
            'passenger_load' => fake()->numberBetween(0, 42),
            'bus_number' => fake()->unique()->numberBetween(100, 999),
        ];
    }
    private function generateSeats()
    {
        $seats = [];
        for ($i = 1; $i <= 42; $i++) {
            $seats[] = [
                'seat_number' => $i,
                'status' => rand(0, 1) ? 'available' : 'occupied'
            ];
        }
        return $seats;
    }
}
