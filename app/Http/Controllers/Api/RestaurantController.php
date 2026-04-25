<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Display a listing of open restaurants.
     */
    public function index(Request $request)
    {
        $query = Restaurant::where('is_open', true);

        if ($request->has('category_id') && $request->category_id) {
            $query->whereHas('products', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $restaurants = $query->with(['products' => function($query) {
                $query->where('is_available', true)->take(5);
            }])
            ->get();

        $restaurants->transform(function ($restaurant) {
            if ($restaurant->cover_image) {
                $restaurant->cover_image = config('app.url') . $restaurant->cover_image;
            }
            return $restaurant;
        });
            
        return response()->json($restaurants);
    }

    /**
     * Store a newly created restaurant in storage.
     */
    public function store(Request $request)
    {
        if (!$request->user()->isRestaurantOwner() && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Apenas donos de restaurantes podem criar restaurantes.'], 403);
        }

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'cuisine_type'   => 'nullable|string|max:255',
            'cover_image'    => 'nullable|string',
            'prep_time_mins' => 'integer|min:1',
            'is_open'        => 'boolean',
        ]);

        $restaurant = $request->user()->restaurants()->create($validated);

        return response()->json($restaurant, 201);
    }

    /**
     * Display the specified restaurant with its full menu.
     */
    public function show($id)
    {
        $restaurant = Restaurant::with(['products.category'])->findOrFail($id);
        
        if ($restaurant->cover_image) {
            $restaurant->cover_image = config('app.url') . $restaurant->cover_image;
        }

        $restaurant->products->transform(function ($product) {
            if ($product->image) {
                $product->image = config('app.url') . $product->image;
            }
            return $product;
        });

        return response()->json($restaurant);
    }

    /**
     * Update the specified restaurant in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        if ($request->user()->id !== $restaurant->user_id && ! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name'           => 'sometimes|required|string|max:255',
            'cuisine_type'   => 'nullable|string|max:255',
            'cover_image'    => 'nullable|string',
            'prep_time_mins' => 'integer|min:1',
            'is_open'        => 'boolean',
        ]);

        $restaurant->update($validated);

        return response()->json($restaurant);
    }

    /**
     * Remove the specified restaurant from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        if (request()->user()->id !== $restaurant->user_id && ! request()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $restaurant->delete();

        return response()->json(null, 204);
    }
}
