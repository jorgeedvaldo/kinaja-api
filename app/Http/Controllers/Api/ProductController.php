<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * List all products for a specific restaurant.
     */
    public function index(Restaurant $restaurant)
    {
        return response()->json($restaurant->products()->with('category')->get());
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        if ($request->user()->id !== $restaurant->user_id && ! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'image'        => 'nullable|string',
            'is_available' => 'boolean',
        ]);

        $product = $restaurant->products()->create($request->all());

        return response()->json($product, 201);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $restaurant = $product->restaurant;
        if ($request->user()->id !== $restaurant->user_id && ! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'category_id'  => 'sometimes|required|exists:categories,id',
            'name'         => 'sometimes|required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'sometimes|required|numeric|min:0',
            'image'        => 'nullable|string',
            'is_available' => 'boolean',
        ]);

        $product->update($request->all());

        return response()->json($product);
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $restaurant = $product->restaurant;
        if (request()->user()->id !== $restaurant->user_id && ! request()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product->delete();

        return response()->json(null, 204);
    }
}
