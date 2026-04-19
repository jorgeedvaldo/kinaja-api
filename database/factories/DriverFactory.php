<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    protected $model = Driver::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->driver(),
            'vehicle_type' => fake()->randomElement(['mota', 'carro']),
            'license_plate' => strtoupper(fake()->bothify('LD-##-##-??')),
            'current_lat' => fake()->latitude(-8.9, -8.7),
            'current_lng' => fake()->longitude(13.1, 13.3),
            'is_online' => fake()->boolean(70),
        ];
    }
}
