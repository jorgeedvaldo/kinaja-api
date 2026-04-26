@extends('admin.layouts.app')

@section('page-title', 'Produtos')
@section('title', 'Produtos')

@section('content')
<div class="table-card" style="margin-bottom: 24px; padding: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <span class="table-title" style="margin: 0;">Gestão de Produtos</span>
        <a href="{{ route('admin.products.create', ['restaurant_id' => request('restaurant_id')]) }}" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Novo Produto
        </a>
    </div>
    <form method="GET" action="{{ route('admin.products.index') }}" style="margin:0; background: #f8fafc; padding: 16px; border-radius: 8px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end;">
        <div>
            <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">Restaurante</label>
            <select name="restaurant_id" class="table-filter" style="width:100%">
                <option value="">Todos</option>
                @foreach($restaurants as $r)
                <option value="{{ $r->id }}" {{ request('restaurant_id') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">Categoria</label>
            <select name="category_id" class="table-filter" style="width:100%">
                <option value="">Todas</option>
                @foreach($categories as $c)
                <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">Disponibilidade</label>
            <select name="is_available" class="table-filter" style="width:100%">
                <option value="">Todos</option>
                <option value="1" {{ request('is_available') === '1' ? 'selected' : '' }}>Disponível</option>
                <option value="0" {{ request('is_available') === '0' ? 'selected' : '' }}>Esgotado</option>
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="flex:1">Filtrar</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Limpar</a>
        </div>
    </form>
</div>

@if($restaurants->isEmpty())
    <div class="loading-center"><p class="text-muted">Nenhum restaurante encontrado. Crie um restaurante primeiro.</p></div>
@elseif($products->isEmpty())
    <div class="table-card"><div class="table-empty">Nenhum produto encontrado com os filtros atuais.</div></div>
@else
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Restaurante</th>
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
                    <td>{{ $product->restaurant->name ?? '—' }}</td>
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
        <div style="padding:16px 24px">{{ $products->links() }}</div>
    </div>
@endif
@endsection
