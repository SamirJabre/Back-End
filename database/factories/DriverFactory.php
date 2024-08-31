<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => fake()->password,
            'phone_number' => fake()->phoneNumber,
            'profile_picture' => fake()->imageUrl(),
            'address' => fake()->address,
            'id_photo' => fake()->imageUrl(),
            'driver_license' => fake()->randomNumber(8),
        ];
    }
}
