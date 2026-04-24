<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $stats = [
                'total_users'       => User::count(),
                'total_restaurants' => Restaurant::count(),
                'total_orders'      => Order::count(),
                'total_revenue'     => Order::where('status', 'delivered')->sum('total_amount'),
            ];
            $recentOrders = Order::with(['client', 'restaurant'])
                ->latest()
                ->take(10)
                ->get();
        } else {
            // Restaurant owner — only their data
            $restaurantIds = $user->restaurants()->pluck('id');
            $orders = Order::whereIn('restaurant_id', $restaurantIds);

            $stats = [
                'pending'   => (clone $orders)->where('status', 'pending')->count(),
                'active'    => (clone $orders)->whereNotIn('status', ['delivered', 'cancelled'])->count(),
                'delivered' => (clone $orders)->where('status', 'delivered')->count(),
                'revenue'   => (clone $orders)->where('status', 'delivered')->sum('total_amount'),
            ];
            $recentOrders = Order::whereIn('restaurant_id', $restaurantIds)
                ->with(['client', 'restaurant'])
                ->latest()
                ->take(10)
                ->get();
        }

        return view('admin.dashboard', compact('stats', 'recentOrders', 'user'));
    }
}
