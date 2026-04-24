<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $restaurants = Restaurant::with('owner')->latest()->get();
        } else {
            $restaurants = $user->restaurants()->latest()->get();
        }

        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        return view('admin.restaurants.form', ['restaurant' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'cuisine_type'   => 'nullable|string|max:255',
            'cover_image'    => 'nullable|string',
            'prep_time_mins' => 'integer|min:1',
            'is_open'        => 'nullable',
        ]);

        $validated['is_open'] = $request->has('is_open');

        $request->user()->restaurants()->create($validated);

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Restaurante criado com sucesso!');
    }

    public function edit(Request $request, Restaurant $restaurant)
    {
        $user = $request->user();
        if (!$user->isAdmin() && $user->id != $restaurant->user_id) {
            abort(403, 'Não autorizado.');
        }

        return view('admin.restaurants.form', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $user = $request->user();
        if (!$user->isAdmin() && $user->id != $restaurant->user_id) {
            abort(403, 'Não autorizado.');
        }

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'cuisine_type'   => 'nullable|string|max:255',
            'cover_image'    => 'nullable|string',
            'prep_time_mins' => 'integer|min:1',
            'is_open'        => 'nullable',
        ]);

        $validated['is_open'] = $request->has('is_open');

        $restaurant->update($validated);

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Restaurante atualizado com sucesso!');
    }

    public function destroy(Request $request, Restaurant $restaurant)
    {
        $user = $request->user();
        if (!$user->isAdmin() && $user->id != $restaurant->user_id) {
            abort(403, 'Não autorizado.');
        }

        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Restaurante apagado com sucesso!');
    }
}
