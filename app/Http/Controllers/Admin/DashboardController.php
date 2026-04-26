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
        $today = \Carbon\Carbon::today();
        $sub7 = \Carbon\Carbon::now()->subDays(7);
        $sub30 = \Carbon\Carbon::now()->subDays(30);

        if ($user->isAdmin()) {
            $totalOrdersCount = Order::count();
            $cancelledOrdersCount = Order::where('status', 'cancelled')->count();
            
            $stats = [
                'total_users'       => User::count(),
                'total_restaurants' => Restaurant::count(),
                
                // Pedidos por período
                'orders_today'      => Order::whereDate('created_at', $today)->count(),
                'orders_7d'         => Order::where('created_at', '>=', $sub7)->count(),
                'orders_30d'        => Order::where('created_at', '>=', $sub30)->count(),
                'total_orders'      => $totalOrdersCount,
                
                // Receita por período
                'revenue_today'     => Order::where('status', 'delivered')->whereDate('created_at', $today)->sum('total_amount'),
                'revenue_7d'        => Order::where('status', 'delivered')->where('created_at', '>=', $sub7)->sum('total_amount'),
                'revenue_30d'       => Order::where('status', 'delivered')->where('created_at', '>=', $sub30)->sum('total_amount'),
                'total_revenue'     => Order::where('status', 'delivered')->sum('total_amount'),
                
                // Métricas calculadas
                'cancellation_rate' => $totalOrdersCount > 0 ? round(($cancelledOrdersCount / $totalOrdersCount) * 100, 1) : 0,
                'avg_delivery_time' => round(Order::where('status', 'delivered')->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_time')->value('avg_time') ?? 0),
            ];

            $recentOrders = Order::with(['client', 'restaurant'])
                ->latest()
                ->take(8)
                ->get();

            $topRestaurants = Restaurant::withSum(['orders as total_revenue' => function ($query) {
                    $query->where('status', 'delivered');
                }], 'total_amount')
                ->orderByDesc('total_revenue')
                ->take(5)
                ->get();

            $alerts = [
                'no_driver' => Order::whereIn('status', ['ready', 'in_transit'])->whereNull('driver_id')->count(),
                'delayed_pending' => Order::where('status', 'pending')->where('created_at', '<=', now()->subMinutes(15))->count(),
                'closed_restaurants' => Restaurant::where('is_open', false)->whereHas('orders', function ($q) {
                    $q->whereNotIn('status', ['delivered', 'cancelled']);
                })->count(),
                'offline_drivers' => \App\Models\Driver::where('is_online', false)->whereHas('orders', function ($q) {
                    $q->whereNotIn('status', ['delivered', 'cancelled']);
                })->count(),
            ];

            return view('admin.dashboard', compact('stats', 'recentOrders', 'topRestaurants', 'user', 'alerts'));

        } else {
            // Restaurant owner — only their data
            $restaurantIds = $user->restaurants()->pluck('id');
            $ordersQuery = Order::whereIn('restaurant_id', $restaurantIds);
            
            $totalOrdersCount = (clone $ordersQuery)->count();
            $cancelledOrdersCount = (clone $ordersQuery)->where('status', 'cancelled')->count();

            $stats = [
                'pending'   => (clone $ordersQuery)->where('status', 'pending')->count(),
                'active'    => (clone $ordersQuery)->whereNotIn('status', ['delivered', 'cancelled'])->count(),
                'delivered' => (clone $ordersQuery)->where('status', 'delivered')->count(),
                
                'revenue'   => (clone $ordersQuery)->where('status', 'delivered')->sum('total_amount'),
                
                'cancellation_rate' => $totalOrdersCount > 0 ? round(($cancelledOrdersCount / $totalOrdersCount) * 100, 1) : 0,
                'avg_prep_time'     => round((clone $ordersQuery)->where('status', 'ready')->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_time')->value('avg_time') ?? 0),
            ];

            $recentOrders = (clone $ordersQuery)
                ->with(['client', 'restaurant'])
                ->latest()
                ->take(8)
                ->get();

            $topProducts = \App\Models\Product::whereIn('restaurant_id', $restaurantIds)
                ->withSum('orderItems as total_sold', 'quantity')
                ->having('total_sold', '>', 0)
                ->orderByDesc('total_sold')
                ->take(5)
                ->get();

            return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'user'));
        }
    }
}
