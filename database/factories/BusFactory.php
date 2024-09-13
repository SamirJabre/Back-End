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
        $seatsData = $this->generateSeats();
        return [
            'driver_id' => Driver::factory(),
            'seats' => json_encode($seatsData['seats']),
            'passenger_load' => $seatsData['occupied_count'],
            'bus_number' => fake()->unique()->numberBetween(100, 999),
        ];
    }
    private function generateSeats()
    {
        $seats = [];
        $occupiedCount = 0;
        for ($i = 1; $i <= 42; $i++) {
            $status = rand(0, 1) ? 'available' : 'occupied';
            if ($status === 'occupied') {
                $occupiedCount++;
            }
            $seats[] = [
                'seat_number' => $i,
                'status' => $status
            ];
        }
        return [
            'seats' => $seats,
            'occupied_count' => $occupiedCount
        ];
    }
}
