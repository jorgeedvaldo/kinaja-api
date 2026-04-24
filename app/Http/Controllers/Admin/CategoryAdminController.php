<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryAdminController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        // Only admin can manage categories
        if (!$request->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem gerir categorias.');
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name|max:255',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function update(Request $request, Category $category)
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem gerir categorias.');
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name,' . $category->id . '|max:255',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Request $request, Category $category)
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem gerir categorias.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria apagada com sucesso!');
    }
}
