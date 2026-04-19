<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->restaurantOwner(),
            'name' => fake()->company(),
            'cuisine_type' => fake()->randomElement(['Angolana', 'Portuguesa', 'Italiana', 'Chinesa', 'Fast Food', 'Sushi']),
            'cover_image' => fake()->imageUrl(640, 480, 'food'),
            'rating' => fake()->randomFloat(1, 1, 5),
            'prep_time_mins' => fake()->numberBetween(15, 60),
            'is_open' => fake()->boolean(80),
        ];
    }
}
