<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => User::factory(),
            'restaurant_id' => Restaurant::factory(),
            'driver_id' => Driver::factory(),
            'total_amount' => fake()->randomFloat(2, 2000, 20000),
            'delivery_fee' => fake()->randomFloat(2, 500, 2000),
            'status' => fake()->randomElement([
                'pending',
                'accepted',
                'preparing',
                'ready',
                'in_transit',
                'delivered',
                'cancelled',
            ]),
        ];
    }
}
