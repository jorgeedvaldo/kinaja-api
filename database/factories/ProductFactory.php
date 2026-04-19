<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'restaurant_id' => Restaurant::factory(),
            'category_id' => Category::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 500, 10000),
            'image' => fake()->imageUrl(640, 480, 'food'),
            'is_available' => fake()->boolean(90),
        ];
    }
}
