@extends('admin.layouts.app')

@section('page-title', 'Dashboard')
@section('title', 'Dashboard')

@section('content')
@php
    $isAdmin = auth()->user()->isAdmin();
@endphp

@if($isAdmin)
    <!-- ADMIN VIEW -->
    @if(array_sum($alerts) > 0)
    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;">
        <h3 style="color: #991b1b; margin-top: 0; margin-bottom: 12px; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Alertas Operacionais
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px;">
            @if($alerts['no_driver'] > 0)
            <div style="background: #fff; border: 1px solid #fca5a5; padding: 12px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #7f1d1d; font-weight: 500;">Pedidos sem motorista</span>
                <span class="badge badge-cancelled" style="font-size: 1rem;">{{ $alerts['no_driver'] }}</span>
            </div>
            @endif
            @if($alerts['delayed_pending'] > 0)
            <div style="background: #fff; border: 1px solid #fca5a5; padding: 12px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #7f1d1d; font-weight: 500;">Pendentes atrasados (>15m)</span>
                <span class="badge badge-cancelled" style="font-size: 1rem;">{{ $alerts['delayed_pending'] }}</span>
            </div>
            @endif
            @if($alerts['closed_restaurants'] > 0)
            <div style="background: #fff; border: 1px solid #fca5a5; padding: 12px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #7f1d1d; font-weight: 500;">Restaurantes fechados c/ pedidos</span>
                <span class="badge badge-cancelled" style="font-size: 1rem;">{{ $alerts['closed_restaurants'] }}</span>
            </div>
            @endif
            @if($alerts['offline_drivers'] > 0)
            <div style="background: #fff; border: 1px solid #fca5a5; padding: 12px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #7f1d1d; font-weight: 500;">Motoristas offline em curso</span>
                <span class="badge badge-cancelled" style="font-size: 1rem;">{{ $alerts['offline_drivers'] }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    <h3 class="mb-3">Visão Global</h3>
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon blue"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['total_users'] }}</div>
            <div class="stat-card-label">Utilizadores</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon yellow"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['total_restaurants'] }}</div>
            <div class="stat-card-label">Restaurantes</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon red"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['cancellation_rate'] }}%</div>
            <div class="stat-card-label">Taxa de Cancelamento</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon green"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['avg_delivery_time'] }} min</div>
            <div class="stat-card-label">Tempo Médio Entrega</div>
        </div>
    </div>

    <h3 class="mb-3 mt-4">Pedidos & Receitas (Entregues)</h3>
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon blue"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['orders_today'] }}</div>
            <div class="stat-card-label">Pedidos Hoje</div>
            <div class="text-sm mt-1 fw-600 text-green">{{ number_format($stats['revenue_today'], 2, ',', '.') }} Kz</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon blue"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['orders_7d'] }}</div>
            <div class="stat-card-label">Pedidos 7 Dias</div>
            <div class="text-sm mt-1 fw-600 text-green">{{ number_format($stats['revenue_7d'], 2, ',', '.') }} Kz</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon blue"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['orders_30d'] }}</div>
            <div class="stat-card-label">Pedidos 30 Dias</div>
            <div class="text-sm mt-1 fw-600 text-green">{{ number_format($stats['revenue_30d'], 2, ',', '.') }} Kz</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon green"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['total_orders'] }}</div>
            <div class="stat-card-label">Total Histórico</div>
            <div class="text-sm mt-1 fw-600 text-green">{{ number_format($stats['total_revenue'], 2, ',', '.') }} Kz</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Tabela Pedidos Recentes -->
        <div class="table-card" style="margin-bottom: 0;">
            <div class="table-header">
                <span class="table-title">Últimos Pedidos Globais</span>
            </div>
            @if($recentOrders->count())
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Restaurante</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->client->name ?? '—' }}</td>
                        <td>{{ $order->restaurant->name ?? '—' }}</td>
                        <td class="fw-600">{{ number_format($order->total_amount, 2, ',', '.') }} Kz</td>
                        <td><span class="badge badge-{{ $order->status }}">
                            @php
                                $statusLabels = ['pending'=>'Pendente','accepted'=>'Aceite','preparing'=>'Preparando','ready'=>'Pronto','in_transit'=>'Em Trânsito','delivered'=>'Entregue','cancelled'=>'Cancelado'];
                            @endphp
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span></td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-secondary">Ver</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="table-empty">Nenhum pedido encontrado.</div>
            @endif
        </div>

        <!-- Top 5 Restaurantes -->
        <div class="table-card" style="margin-bottom: 0;">
            <div class="table-header">
                <span class="table-title">Top 5 Restaurantes</span>
            </div>
            @if($topRestaurants->count())
            <table class="table-sm">
                <thead>
                    <tr>
                        <th>Restaurante</th>
                        <th class="text-right">Receita</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topRestaurants as $rest)
                    <tr>
                        <td><strong>{{ $rest->name }}</strong></td>
                        <td class="text-right text-green fw-600">{{ number_format($rest->total_revenue ?? 0, 2, ',', '.') }} Kz</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="table-empty">Sem dados suficientes.</div>
            @endif
        </div>
    </div>

@else
    <!-- RESTAURANT VIEW -->
    <h3 class="mb-3">Resumo da Operação</h3>
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon yellow"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['pending'] }}</div>
            <div class="stat-card-label">Pendentes</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon blue"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['active'] }}</div>
            <div class="stat-card-label">Em Preparação/Trânsito</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon green"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['delivered'] }}</div>
            <div class="stat-card-label">Entregues</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon green"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div></div>
            <div class="stat-card-value">{{ number_format($stats['revenue'], 2, ',', '.') }} Kz</div>
            <div class="stat-card-label">Receita Acumulada</div>
        </div>
    </div>

    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon red"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['cancellation_rate'] }}%</div>
            <div class="stat-card-label">Taxa de Cancelamento</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header"><div class="stat-card-icon yellow"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div></div>
            <div class="stat-card-value">{{ $stats['avg_prep_time'] }} min</div>
            <div class="stat-card-label">Tempo Médio de Preparação</div>
            <div class="text-sm mt-1 text-muted">(estimativa)</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Tabela Pedidos Recentes -->
        <div class="table-card" style="margin-bottom: 0;">
            <div class="table-header">
                <span class="table-title">Últimos Pedidos</span>
            </div>
            @if($recentOrders->count())
            <table>
                <thead>
                    <tr>
                        <th>#</th>
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
                        <td>{{ $order->restaurant->name ?? '—' }}</td>
                        <td class="fw-600">{{ number_format($order->total_amount, 2, ',', '.') }} Kz</td>
                        <td><span class="badge badge-{{ $order->status }}">
                            @php
                                $statusLabels = ['pending'=>'Pendente','accepted'=>'Aceite','preparing'=>'Preparando','ready'=>'Pronto','in_transit'=>'Em Trânsito','delivered'=>'Entregue','cancelled'=>'Cancelado'];
                            @endphp
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span></td>
                        <td class="text-sm text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-secondary">Ver</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="table-empty">Nenhum pedido encontrado.</div>
            @endif
        </div>

        <!-- Top 5 Produtos -->
        <div class="table-card" style="margin-bottom: 0;">
            <div class="table-header">
                <span class="table-title">Produtos Mais Vendidos</span>
            </div>
            @if($topProducts->count())
            <table class="table-sm">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th class="text-center">Qtd</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $prod)
                    <tr>
                        <td>
                            <strong>{{ $prod->name }}</strong><br>
                            <span class="text-sm text-muted">{{ $prod->restaurant->name ?? '' }}</span>
                        </td>
                        <td class="text-center fw-600">{{ $prod->total_sold }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="table-empty">Sem vendas registadas.</div>
            @endif
        </div>
    </div>
@endif
@endsection
