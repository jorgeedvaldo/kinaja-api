@extends('admin.layouts.app')

@section('page-title', 'Categorias')
@section('title', 'Categorias')

@section('content')
@php $isAdmin = auth()->user()->isAdmin(); @endphp

<div class="table-card">
    <div class="table-header">
        <span class="table-title">Categorias ({{ $categories->count() }})</span>
        @if($isAdmin)
        <div class="table-actions">
            <button type="button" class="btn btn-primary" onclick="document.getElementById('new-cat-form').classList.toggle('hidden')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nova Categoria
            </button>
        </div>
        @endif
    </div>

    {{-- Inline new category form --}}
    @if($isAdmin)
    <div id="new-cat-form" class="hidden" style="padding:16px 24px;border-bottom:1px solid var(--border);background:var(--bg)">
        <form method="POST" action="{{ route('admin.categories.store') }}" style="display:flex;align-items:flex-end;gap:10px;flex-wrap:wrap">
            @csrf
            <div class="form-group" style="margin-bottom:0;flex:1;min-width:200px">
                <label for="cf-name">Nome da Categoria</label>
                <input type="text" id="cf-name" name="name" required placeholder="Ex: Pizzas, Bebidas, Sobremesas">
            </div>
            <button type="submit" class="btn btn-primary">Criar</button>
        </form>
    </div>
    @endif

    @if($categories->count())
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Produtos</th>
                <th>Data Criação</th>
                @if($isAdmin)<th>Ações</th>@endif
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>
                    @if($isAdmin && request()->query('edit') == $category->id)
                        <form method="POST" action="{{ route('admin.categories.update', $category) }}" style="display:flex;align-items:center;gap:8px;margin:0">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $category->name }}" required style="padding:6px 10px;border:2px solid var(--border-med);border-radius:6px;font-size:14px;width:200px">
                            <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-secondary">Cancelar</a>
                        </form>
                    @else
                        <span class="fw-600">{{ $category->name }}</span>
                    @endif
                </td>
                <td>{{ $category->products_count }}</td>
                <td class="text-sm text-muted">{{ $category->created_at->format('d/m/Y H:i') }}</td>
                @if($isAdmin)
                <td class="flex gap-8">
                    <a href="{{ route('admin.categories.index', ['edit' => $category->id]) }}" class="btn btn-sm btn-secondary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="margin:0" onsubmit="return confirm('Tem certeza?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </form>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="table-empty">Nenhuma categoria.</div>
    @endif
</div>
@endsection
