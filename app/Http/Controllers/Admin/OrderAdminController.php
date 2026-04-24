<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $status = $request->query('status');

        $query = Order::with(['client', 'restaurant', 'driver.user'])->latest();

        if ($user->isAdmin()) {
            // Admin sees all
        } else {
            // Restaurant owner only sees orders for their restaurants
            $restaurantIds = $user->restaurants()->pluck('id');
            $query->whereIn('restaurant_id', $restaurantIds);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function show(Request $request, Order $order)
    {
        $user = $request->user();

        // Ownership check
        if (!$user->isAdmin()) {
            $isOwner = $user->restaurants()->where('id', $order->restaurant_id)->exists();
            if (!$isOwner) {
                abort(403, 'Não autorizado.');
            }
        }

        $order->load(['client', 'restaurant', 'driver.user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $user = $request->user();

        // Ownership check
        if (!$user->isAdmin()) {
            $isOwner = $user->restaurants()->where('id', $order->restaurant_id)->exists();
            if (!$isOwner) {
                abort(403, 'Não autorizado.');
            }
        }

        $validated = $request->validate([
            'status' => 'required|string|in:accepted,preparing,ready,in_transit,delivered,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        $statusLabels = [
            'accepted'   => 'Aceite',
            'preparing'  => 'Preparando',
            'ready'      => 'Pronto',
            'in_transit' => 'Em Trânsito',
            'delivered'  => 'Entregue',
            'cancelled'  => 'Cancelado',
        ];

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Pedido #' . $order->id . ' → ' . ($statusLabels[$validated['status']] ?? $validated['status']));
    }
}
