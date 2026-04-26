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

        $query = Order::with(['client', 'restaurant', 'driver.user'])
            ->orderByRaw("FIELD(status, 'pending', 'accepted', 'ready', 'preparing', 'in_transit', 'delivered', 'cancelled')")
            ->latest();

        if ($user->isAdmin()) {
            $restaurants = \App\Models\Restaurant::orderBy('name')->get();
        } else {
            $restaurantIds = $user->restaurants()->pluck('id');
            $query->whereIn('restaurant_id', $restaurantIds);
            $restaurants = $user->restaurants()->orderBy('name')->get();
        }

        $query->when($request->status, fn($q, $v) => $q->where('status', $v))
              ->when($request->date_from, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
              ->when($request->date_to, fn($q, $v) => $q->whereDate('created_at', '<=', $v))
              ->when($request->restaurant_id, fn($q, $v) => $q->where('restaurant_id', $v))
              ->when($request->min_total, fn($q, $v) => $q->where('total_amount', '>=', $v))
              ->when($request->max_total, fn($q, $v) => $q->where('total_amount', '<=', $v))
              ->when($request->driver_id, fn($q, $v) => $q->where('driver_id', $v));

        $orders = $query->paginate(20)->withQueryString();
        $drivers = \App\Models\Driver::with('user')->get();

        return view('admin.orders.index', compact('orders', 'restaurants', 'drivers'));
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

        if (in_array($order->status, ['delivered', 'cancelled'])) {
            $totalTime = $order->created_at->diffForHumans($order->updated_at, true);
        } else {
            $totalTime = $order->created_at->diffForHumans(now(), true);
        }

        return view('admin.orders.show', compact('order', 'totalTime'));
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
