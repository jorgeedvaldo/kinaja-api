@extends('admin.layouts.app')

@section('page-title', $product ? 'Editar Produto' : 'Novo Produto')
@section('title', $product ? 'Editar Produto' : 'Novo Produto')

@section('content')
<div class="table-card" style="max-width:640px">
    <div class="table-header">
        <span class="table-title">{{ $product ? 'Editar Produto' : 'Novo Produto' }}</span>
    </div>
    <div style="padding:24px">
        <form method="POST" action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}">
            @csrf
            @if($product) @method('PUT') @endif

            <div class="form-group">
                <label for="pf-restaurant">Restaurante</label>
                <select id="pf-restaurant" name="restaurant_id" required>
                    @foreach($restaurants as $r)
                    <option value="{{ $r->id }}" {{ old('restaurant_id', $selectedRestaurantId) == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="pf-name">Nome</label>
                <input type="text" id="pf-name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="pf-desc">Descrição</label>
                <textarea id="pf-desc" name="description" rows="2">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="pf-price">Preço (Kz)</label>
                <input type="number" step="0.01" id="pf-price" name="price" value="{{ old('price', $product->price ?? '') }}" required min="0">
            </div>

            <div class="form-group">
                <label for="pf-cat">Categoria</label>
                <select id="pf-cat" name="category_id" required>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="pf-image">Imagem (URL)</label>
                <input type="text" id="pf-image" name="image" value="{{ old('image', $product->image ?? '') }}" placeholder="https://...">
            </div>

            <div class="form-group flex items-center gap-8">
                <label class="toggle">
                    <input type="checkbox" name="is_available" {{ old('is_available', $product->is_available ?? true) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
                <span>Disponível</span>
            </div>

            <div style="display:flex;gap:10px;padding-top:16px">
                <a href="{{ route('admin.products.index', ['restaurant_id' => $selectedRestaurantId]) }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">{{ $product ? 'Salvar' : 'Criar' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
