@extends('admin.layouts.app')

@section('page-title', 'Dashboard')
@section('title', 'Dashboard')

@section('content')
@php
    $isAdmin = auth()->user()->isAdmin();
@endphp

<div class="stats-grid">
    @if($isAdmin)
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon red">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $stats['total_users'] }}</div>
            <div class="stat-card-label">Utilizadores</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon yellow">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/></svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $stats['total_restaurants'] }}</div>
            <div class="stat-card-label">Restaurantes</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon blue">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $stats['total_orders'] }}</div>
            <div class="stat-card-label">Total Pedidos</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon green">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['total_revenue'], 2, ',', '.') }} Kz</div>
            <div class="stat-card-label">Receita (Entregues)</div>
        </div>
    @else
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon yellow"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['pending'] }}</div>
            <div class="stat-card-label">Pendentes</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon blue"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['active'] }}</div>
            <div class="stat-card-label">Activos</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon green"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['delivered'] }}</div>
            <div class="stat-card-label">Entregues</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon red"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg></div></div>
            <div class="stat-card-value">{{ number_format($stats['revenue'], 2, ',', '.') }} Kz</div>
            <div class="stat-card-label">Receita</div>
        </div>
    @endif
</div>

<div class="table-card">
    <div class="table-header">
        <span class="table-title">{{ $isAdmin ? 'Pedidos Recentes' : 'Últimos Pedidos' }}</span>
    </div>
    @if($recentOrders->count())
    <table>
        <thead>
            <tr>
                <th>#</th>
                @if($isAdmin)<th>Cliente</th>@endif
                <th>Restaurante</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Data</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentOrders as $order)
            <tr>
                <td><strong>#{{ $order->id }}</strong></td>
                @if($isAdmin)<td>{{ $order->client->name ?? '—' }}</td>@endif
                <td>{{ $order->restaurant->name ?? '—' }}</td>
                <td class="fw-600">{{ number_format($order->total_amount, 2, ',', '.') }} Kz</td>
                <td><span class="badge badge-{{ $order->status }}">
                    @php
                        $statusLabels = ['pending'=>'Pendente','accepted'=>'Aceite','preparing'=>'Preparando','ready'=>'Pronto','in_transit'=>'Em Trânsito','delivered'=>'Entregue','cancelled'=>'Cancelado'];
                    @endphp
                    {{ $statusLabels[$order->status] ?? $order->status }}
                </span></td>
                <td class="text-sm text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Ver
                </a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="table-empty">Nenhum pedido encontrado.</div>
    @endif
</div>
@endsection
