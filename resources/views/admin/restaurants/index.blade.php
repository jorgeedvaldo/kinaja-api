@extends('admin.layouts.app')

@section('page-title', 'Restaurantes')
@section('title', 'Restaurantes')

@section('content')
<div class="table-card">
    <div class="table-header">
        <span class="table-title">Restaurantes ({{ $restaurants->count() }})</span>
        <div class="table-actions">
            @if(auth()->user()->isRestaurantOwner() || auth()->user()->isAdmin())
            <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Novo Restaurante
            </a>
            @endif
        </div>
    </div>
    @if($restaurants->count())
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Cozinha</th>
                <th>Rating</th>
                <th>Prep. (min)</th>
                <th>Estado</th>
                @if(auth()->user()->isAdmin())<th>Dono</th>@endif
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($restaurants as $restaurant)
            <tr>
                <td>{{ $restaurant->id }}</td>
                <td class="fw-600">{{ $restaurant->name }}</td>
                <td>{{ $restaurant->cuisine_type ?: '—' }}</td>
                <td>⭐ {{ number_format($restaurant->rating, 1) }}</td>
                <td>{{ $restaurant->prep_time_mins }} min</td>
                <td>
                    <span class="badge badge-{{ $restaurant->is_open ? 'open' : 'closed' }}">
                        {{ $restaurant->is_open ? 'Aberto' : 'Fechado' }}
                    </span>
                </td>
                @if(auth()->user()->isAdmin())
                <td class="text-sm">{{ $restaurant->owner->name ?? '—' }}</td>
                @endif
                <td class="flex gap-8">
                    <a href="{{ route('admin.restaurants.edit', $restaurant) }}" class="btn btn-sm btn-secondary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </a>
                    <form method="POST" action="{{ route('admin.restaurants.destroy', $restaurant) }}" style="margin:0" onsubmit="return confirm('Tem certeza que deseja apagar este restaurante?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="table-empty">Nenhum restaurante encontrado.</div>
    @endif
</div>
@endsection
