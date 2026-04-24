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
     * List all users (admin only).
     */
    public function users(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Acesso restrito a administradores.'], 403);
        }

        return response()->json(User::with(['driver', 'restaurants'])->latest()->get());
    }

    /**
     * List all orders in the system (admin only).
     */
    public function orders(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Acesso restrito a administradores.'], 403);
        }

        return response()->json(Order::with(['client', 'restaurant', 'driver.user'])->latest()->get());
    }

    /**
     * Get dashboard statistics (admin only).
     */
    public function dashboard(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Acesso restrito a administradores.'], 403);
        }

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

    /**
     * List restaurants — admin sees all, owner sees own.
     */
    public function restaurants(Request $request)
    {
        if ($request->user()->isAdmin()) {
            return response()->json(Restaurant::all());
        }

        if ($request->user()->isRestaurantOwner()) {
            return response()->json($request->user()->restaurants);
        }

        return response()->json(['message' => 'Acesso não autorizado.'], 403);
    }
}
