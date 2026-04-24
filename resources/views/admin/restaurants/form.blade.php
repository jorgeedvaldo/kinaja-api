@extends('admin.layouts.app')

@section('page-title', $restaurant ? 'Editar Restaurante' : 'Novo Restaurante')
@section('title', $restaurant ? 'Editar Restaurante' : 'Novo Restaurante')

@section('content')
<div class="table-card" style="max-width:640px">
    <div class="table-header">
        <span class="table-title">{{ $restaurant ? 'Editar Restaurante' : 'Novo Restaurante' }}</span>
    </div>
    <div style="padding:24px">
        <form method="POST" action="{{ $restaurant ? route('admin.restaurants.update', $restaurant) : route('admin.restaurants.store') }}">
            @csrf
            @if($restaurant) @method('PUT') @endif

            <div class="form-group">
                <label for="rf-name">Nome</label>
                <input type="text" id="rf-name" name="name" value="{{ old('name', $restaurant->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="rf-cuisine">Tipo de Cozinha</label>
                <input type="text" id="rf-cuisine" name="cuisine_type" value="{{ old('cuisine_type', $restaurant->cuisine_type ?? '') }}" placeholder="Ex: Angolana, Italiana, Chinesa">
            </div>

            <div class="form-group">
                <label for="rf-prep">Tempo de Preparação (min)</label>
                <input type="number" id="rf-prep" name="prep_time_mins" value="{{ old('prep_time_mins', $restaurant->prep_time_mins ?? 30) }}" min="1">
            </div>

            <div class="form-group">
                <label for="rf-image">Imagem (URL)</label>
                <input type="text" id="rf-image" name="cover_image" value="{{ old('cover_image', $restaurant->cover_image ?? '') }}" placeholder="https://...">
            </div>

            <div class="form-group flex items-center gap-8">
                <label class="toggle">
                    <input type="checkbox" name="is_open" {{ old('is_open', $restaurant->is_open ?? false) ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
                <span>Aberto</span>
            </div>

            <div style="display:flex;gap:10px;padding-top:16px">
                <a href="{{ route('admin.restaurants.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">{{ $restaurant ? 'Salvar' : 'Criar' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
