<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverAdminController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem gerir motoristas.');
        }

        $query = Driver::with(['user', 'orders' => function($q) {
            $q->whereNotIn('status', ['delivered', 'cancelled']);
        }]);

        $query->when($request->filled('status'), function ($q) use ($request) {
            if ($request->status === 'online') {
                $q->where('is_online', true);
            } elseif ($request->status === 'offline') {
                $q->where('is_online', false);
            }
        });

        $query->when($request->filled('availability'), function ($q) use ($request) {
            if ($request->availability === 'free') {
                $q->where('is_online', true)->whereDoesntHave('orders', function($subq) {
                    $subq->whereNotIn('status', ['delivered', 'cancelled']);
                });
            } elseif ($request->availability === 'busy') {
                $q->whereHas('orders', function($subq) {
                    $subq->whereNotIn('status', ['delivered', 'cancelled']);
                });
            }
        });

        $drivers = $query->latest()->paginate(20)->withQueryString();

        return view('admin.drivers.index', compact('drivers'));
    }

    public function locations(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $drivers = Driver::with('user')
            ->where('is_online', true)
            ->whereNotNull('current_lat')
            ->whereNotNull('current_lng')
            ->get();

        $data = $drivers->map(function ($d) {
            $busy = $d->orders()->whereNotIn('status', ['delivered', 'cancelled'])->exists();
            return [
                'id' => $d->id,
                'name' => $d->user->name ?? 'Motorista #' . $d->id,
                'lat' => (float) $d->current_lat,
                'lng' => (float) $d->current_lng,
                'status' => $busy ? 'busy' : 'free',
            ];
        });

        return response()->json($data);
    }
}
