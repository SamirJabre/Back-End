<?php

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
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
            'routes' => json_encode([
            [
                'start_stop' => fake()->city,
                'end_stop' => fake()->city,
            ],
            [
                'start_stop' => fake()->city,
                'end_stop' => fake()->city,
            ]
        ]),
            'price' => fake()->randomNumber(2),
            'departure_time' => fake()->dateTime,
            'arrival_time' => fake()->dateTime,
            'from' => fake()->city,
            'to' => fake()->city,
        ];
    }
}
