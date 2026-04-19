<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * List orders for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Order::with(['restaurant', 'items.product', 'driver.user']);

        if ($user->isClient()) {
            $query->where('client_id', $user->id);
        } elseif ($user->isDriver()) {
            $query->where('driver_id', $user->driver->id ?? 0);
        } elseif ($user->isRestaurantOwner()) {
            $query->whereIn('restaurant_id', $user->restaurants->pluck('id'));
        }

        return response()->json($query->latest()->get());
    }

    /**
     * Place a new order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'delivery_fee'  => 'required|numeric|min:0',
            'delivery_address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'items'         => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.notes'      => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $totalAmount = 0;
            $orderItems = [];

            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                $unitPrice = $product->price;
                $lineTotal = $unitPrice * $itemData['quantity'];
                $totalAmount += $lineTotal;

                $orderItems[] = [
                    'product_id' => $itemData['product_id'],
                    'quantity'   => $itemData['quantity'],
                    'unit_price' => $unitPrice,
                    'notes'      => $itemData['notes'] ?? null,
                ];
            }

            $order = Order::create([
                'client_id'     => $request->user()->id,
                'restaurant_id' => $request->restaurant_id,
                'total_amount'  => $totalAmount,
                'delivery_fee'  => $request->delivery_fee,
                'status'        => 'pending',
                'delivery_address' => $request->delivery_address,
                'latitude'      => $request->latitude,
                'longitude'     => $request->longitude,
            ]);

            $order->items()->createMany($orderItems);

            return response()->json($order->load('items.product'), 201);
        });
    }

    /**
     * Display order details.
     */
    public function show(Order $order)
    {
        // Simple authorization check
        $user = request()->user();
        if ($user->id !== $order->client_id && 
            !$user->isAdmin() && 
            ($user->isRestaurantOwner() && $order->restaurant->user_id !== $user->id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($order->load(['restaurant', 'items.product', 'driver.user', 'client']));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:accepted,preparing,ready,in_transit,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json($order);
    }

    /**
     * Cancel an order.
     */
    public function cancel(Order $order)
    {
        if ($order->status === 'delivered') {
            return response()->json(['message' => 'Cannot cancel a delivered order'], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json($order);
    }
}
