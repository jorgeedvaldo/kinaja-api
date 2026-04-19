<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create 20 Categories
        $categories = \App\Models\Category::factory(20)->create();
        echo "Created 20 categories.\n";

        // 2. Create 50 Restaurant Owners and their Restaurants
        $restaurants = \App\Models\Restaurant::factory(50)->create();
        echo "Created 50 restaurants with owners.\n";

        // 3. Create 50 Drivers
        $drivers = \App\Models\Driver::factory(50)->create();
        echo "Created 50 drivers.\n";

        // 4. Create 100 Client Users
        $clients = \App\Models\User::factory(100)->create(['role' => 'client']);
        echo "Created 100 clients.\n";

        // 5. Create 100 Products
        foreach(range(1, 100) as $i) {
            \App\Models\Product::factory()->create([
                'category_id' => $categories->random()->id,
                'restaurant_id' => $restaurants->random()->id,
            ]);
        }
        echo "Created 100 products.\n";

        // 6. Create 100 Orders
        foreach(range(1, 100) as $i) {
            \App\Models\Order::factory()->create([
                'client_id' => $clients->random()->id,
                'restaurant_id' => $restaurants->random()->id,
                'driver_id' => $drivers->random()->id,
            ]);
        }
        $orders = \App\Models\Order::all();
        echo "Created 100 orders.\n";

        // 7. Create 300 Order Items
        $products = \App\Models\Product::all();
        foreach(range(1, 300) as $i) {
            \App\Models\OrderItem::factory()->create([
                'order_id' => $orders->random()->id,
                'product_id' => $products->random()->id,
            ]);
        }
        echo "Created 300 order items.\n";
    }
}
