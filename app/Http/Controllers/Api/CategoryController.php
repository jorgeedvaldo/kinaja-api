<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        return response()->json(Category::all());
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        return response()->json($category);
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Apenas administradores podem criar categorias.'], 403);
        }

        $validated = $request->validate(['name' => 'required|string|unique:categories,name|max:255']);

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Apenas administradores podem editar categorias.'], 403);
        }

        $validated = $request->validate(['name' => 'required|string|unique:categories,name,' . $category->id . '|max:255']);

        $category->update($validated);

        return response()->json($category);
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Request $request, Category $category)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Apenas administradores podem apagar categorias.'], 403);
        }

        $category->delete();

        return response()->json(null, 204);
    }
}
