<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\Driver;
use DateTime;
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
            'bus_id' => Bus::factory(),
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
            'date' => fake()->dateTimeBetween('now', '+1 week')->format('Y-m-d'),
            'departure_time' => function () {
                $hour = fake()->numberBetween(0, 23);
                $minute = fake()->randomElement([0, 30]);
                return sprintf('%02d:%02d', $hour, $minute);
            },
            'arrival_time' => function (array $attributes) {
                $departureTime = DateTime::createFromFormat('H:i', $attributes['departure_time']);
                $hoursToAdd = fake()->numberBetween(1, 3);
                $arrivalTime = $departureTime->modify("+{$hoursToAdd} hours");
                return $arrivalTime->format('H:i');
            },
            'from' => fake()->city,
            'to' => fake()->city,
        ];
    }
}
