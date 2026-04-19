<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * List all users.
     */
    public function users()
    {
        return response()->json(User::with(['driver', 'restaurants'])->latest()->get());
    }

    /**
     * List all orders in the system.
     */
    public function orders()
    {
        return response()->json(Order::with(['client', 'restaurant', 'driver.user'])->latest()->get());
    }

    /**
     * Get dashboard statistics.
     */
    public function dashboard()
    {
        return response()->json([
            'stats' => [
                'total_users'       => User::count(),
                'total_restaurants' => Restaurant::count(),
                'total_orders'      => Order::count(),
                'total_revenue'     => Order::where('status', 'delivered')->sum('total_amount'),
            ],
            'recent_orders' => Order::with(['client', 'restaurant'])->latest()->take(5)->get()
        ]);
    }
}
