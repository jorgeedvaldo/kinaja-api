<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Create or update driver profile.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'vehicle_type'  => 'required|in:mota,carro',
            'license_plate' => 'required|string|max:255',
        ]);

        $driver = Driver::updateOrCreate(
            ['user_id' => $request->user()->id],
            $request->all()
        );

        return response()->json($driver);
    }

    /**
     * Toggle online/offline status.
     */
    public function toggleOnline(Request $request)
    {
        $driver = $request->user()->driver;
        
        if (!$driver) {
            return response()->json(['message' => 'Driver profile not found'], 404);
        }

        $driver->update(['is_online' => !$driver->is_online]);

        return response()->json($driver);
    }

    /**
     * Update current GPS location.
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'current_lat' => 'required|numeric',
            'current_lng' => 'required|numeric',
        ]);

        $driver = $request->user()->driver;
        
        if (!$driver) {
            return response()->json(['message' => 'Driver profile not found'], 404);
        }

        $driver->update([
            'current_lat' => $request->current_lat,
            'current_lng' => $request->current_lng,
        ]);

        return response()->json(['message' => 'Location updated']);
    }

    /**
     * List available orders for pickup.
     */
    public function availableOrders()
    {
        $orders = Order::where('status', 'ready')
            ->whereNull('driver_id')
            ->with(['restaurant', 'items.product', 'client'])
            ->latest()
            ->get();

        return response()->json($orders);
    }

    /**
     * Accept an order for delivery.
     */
    public function acceptOrder(Request $request, Order $order)
    {
        $driver = $request->user()->driver;

        if (!$driver || !$driver->is_online) {
            return response()->json(['message' => 'Driver must be online to accept orders'], 422);
        }

        if ($order->driver_id) {
            return response()->json(['message' => 'Order already taken'], 422);
        }

        $order->update([
            'driver_id' => $driver->id,
            'status'    => 'in_transit'
        ]);

        return response()->json($order->load(['restaurant', 'client']));
    }
}
