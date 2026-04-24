<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $restaurants = Restaurant::orderBy('name')->get();
        } else {
            $restaurants = $user->restaurants()->orderBy('name')->get();
        }

        $selectedRestaurantId = $request->query('restaurant_id', $restaurants->first()->id ?? null);

        $products = collect();
        if ($selectedRestaurantId) {
            // Verify ownership if not admin
            if (!$user->isAdmin()) {
                $restaurant = $user->restaurants()->find($selectedRestaurantId);
                if (!$restaurant) {
                    abort(403, 'Não autorizado.');
                }
            }
            $products = Product::where('restaurant_id', $selectedRestaurantId)
                ->with('category')
                ->orderBy('name')
                ->get();
        }

        return view('admin.products.index', compact('restaurants', 'products', 'selectedRestaurantId'));
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $restaurantId = $request->query('restaurant_id');

        if ($user->isAdmin()) {
            $restaurants = Restaurant::orderBy('name')->get();
        } else {
            $restaurants = $user->restaurants()->orderBy('name')->get();
        }

        $categories = Category::orderBy('name')->get();

        return view('admin.products.form', [
            'product'              => null,
            'restaurants'          => $restaurants,
            'categories'           => $categories,
            'selectedRestaurantId' => $restaurantId ?: ($restaurants->first()->id ?? null),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'image'         => 'nullable|string',
            'is_available'  => 'nullable',
        ]);

        // Ownership check
        if (!$user->isAdmin()) {
            $restaurant = $user->restaurants()->find($validated['restaurant_id']);
            if (!$restaurant) {
                abort(403, 'Não autorizado.');
            }
        }

        $validated['is_available'] = $request->has('is_available');

        Product::create($validated);

        return redirect()->route('admin.products.index', ['restaurant_id' => $validated['restaurant_id']])
            ->with('success', 'Produto criado com sucesso!');
    }

    public function edit(Request $request, Product $product)
    {
        $user = $request->user();
        $restaurant = $product->restaurant;
        if (!$user->isAdmin() && (!$restaurant || $user->id != $restaurant->user_id)) {
            abort(403, 'Não autorizado.');
        }

        if ($user->isAdmin()) {
            $restaurants = Restaurant::orderBy('name')->get();
        } else {
            $restaurants = $user->restaurants()->orderBy('name')->get();
        }

        $categories = Category::orderBy('name')->get();

        return view('admin.products.form', [
            'product'              => $product,
            'restaurants'          => $restaurants,
            'categories'           => $categories,
            'selectedRestaurantId' => $product->restaurant_id,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $user = $request->user();
        $restaurant = $product->restaurant;
        if (!$user->isAdmin() && (!$restaurant || $user->id != $restaurant->user_id)) {
            abort(403, 'Não autorizado.');
        }

        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'image'         => 'nullable|string',
            'is_available'  => 'nullable',
        ]);

        // Ownership check on new restaurant_id
        if (!$user->isAdmin()) {
            $restaurant = $user->restaurants()->find($validated['restaurant_id']);
            if (!$restaurant) {
                abort(403, 'Não autorizado.');
            }
        }

        $validated['is_available'] = $request->has('is_available');

        $product->update($validated);

        return redirect()->route('admin.products.index', ['restaurant_id' => $validated['restaurant_id']])
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Request $request, Product $product)
    {
        $user = $request->user();
        $restaurant = $product->restaurant;
        if (!$user->isAdmin() && (!$restaurant || $user->id != $restaurant->user_id)) {
            abort(403, 'Não autorizado.');
        }

        $restaurantId = $product->restaurant_id;
        $product->delete();

        return redirect()->route('admin.products.index', ['restaurant_id' => $restaurantId])
            ->with('success', 'Produto apagado com sucesso!');
    }
}
