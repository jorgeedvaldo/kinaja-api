@extends('admin.layouts.app')

@section('page-title', 'Produtos')
@section('title', 'Produtos')

@section('content')
<div class="flex items-center justify-between mb-16 flex-wrap gap-8">
    <div class="flex items-center gap-8">
        <label class="fw-600 text-sm">Restaurante:</label>
        <form method="GET" action="{{ route('admin.products.index') }}" style="margin:0;display:flex;align-items:center;gap:8px">
            <select name="restaurant_id" class="table-filter" onchange="this.form.submit()">
                @foreach($restaurants as $r)
                <option value="{{ $r->id }}" {{ $selectedRestaurantId == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                @endforeach
            </select>
        </form>
    </div>
    @if($selectedRestaurantId)
    <a href="{{ route('admin.products.create', ['restaurant_id' => $selectedRestaurantId]) }}" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Novo Produto
    </a>
    @endif
</div>

@if($restaurants->isEmpty())
    <div class="loading-center"><p class="text-muted">Nenhum restaurante encontrado. Crie um restaurante primeiro.</p></div>
@elseif($products->isEmpty())
    <div class="table-card"><div class="table-empty">Nenhum produto neste restaurante.</div></div>
@else
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Disponível</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td class="fw-600">{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '—' }}</td>
                    <td class="fw-600">{{ number_format($product->price, 2, ',', '.') }} Kz</td>
                    <td>
                        <span class="badge badge-{{ $product->is_available ? 'open' : 'closed' }}">
                            {{ $product->is_available ? 'Sim' : 'Não' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:8px;align-items:center">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-secondary">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="margin:0;display:inline" onsubmit="return confirm('Tem certeza que deseja apagar este produto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
