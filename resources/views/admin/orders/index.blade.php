@extends('admin.layouts.app')

@section('page-title', 'Pedidos')
@section('title', 'Pedidos')

@php
    $statusLabels = ['pending'=>'Pendente','accepted'=>'Aceite','preparing'=>'Preparando','ready'=>'Pronto','in_transit'=>'Em Trânsito','delivered'=>'Entregue','cancelled'=>'Cancelado'];
    $statusNext = ['pending'=>'accepted','accepted'=>'preparing','preparing'=>'ready','ready'=>'in_transit','in_transit'=>'delivered'];
@endphp

@section('content')
<style>
    .badge-pending { background-color: #fef08a; color: #854d0e; }
    .badge-accepted { background-color: #bfdbfe; color: #1e40af; }
    .badge-preparing { background-color: #e9d5ff; color: #6b21a8; }
    .badge-ready { background-color: #bbf7d0; color: #166534; }
    .badge-in_transit { background-color: #fed7aa; color: #9a3412; }
    .badge-delivered { background-color: #86efac; color: #14532d; }
    .badge-cancelled { background-color: #fecaca; color: #991b1b; }
    
    .row-alert-danger td { background-color: #fef2f2 !important; }
    .row-alert-warning td { background-color: #fffbeb !important; }
    .text-alert-danger { color: #dc2626; font-weight: 600; }
    .text-alert-warning { color: #d97706; font-weight: 600; }
    
    .quick-actions form { display: inline; margin-right: 4px; }
</style>

<div class="table-card">
    <div class="table-header" style="flex-direction: column; align-items: stretch; gap: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span class="table-title">Pedidos</span>
        </div>
        <form method="GET" action="{{ route('admin.orders.index') }}" style="margin:0; background: #f8fafc; padding: 16px; border-radius: 8px; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; align-items: end;">
            <div>
                <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">Status</label>
                <select name="status" class="table-filter" style="width:100%">
                    <option value="">Todos</option>
                    @foreach($statusLabels as $key => $label)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">Restaurante</label>
                <select name="restaurant_id" class="table-filter" style="width:100%">
                    <option value="">Todos</option>
                    @foreach($restaurants as $r)
                    <option value="{{ $r->id }}" {{ request('restaurant_id') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->isAdmin())
            <div>
                <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">Motorista</label>
                <select name="driver_id" class="table-filter" style="width:100%">
                    <option value="">Todos</option>
                    @foreach($drivers as $d)
                    <option value="{{ $d->id }}" {{ request('driver_id') == $d->id ? 'selected' : '' }}>{{ $d->user->name ?? 'Entregador #'.$d->id }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div>
                <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">De (Data)</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="table-filter" style="width:100%">
            </div>
            <div>
                <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">Até (Data)</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="table-filter" style="width:100%">
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary" style="flex:1">Filtrar</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Limpar</a>
            </div>
        </form>
    </div>
    @if($orders->count())
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Restaurante</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Tempo</th>
                <th style="min-width: 200px">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            @php
                $diffInMins = $order->created_at->diffInMinutes(now());
                $isPendingAlert = ($order->status === 'pending' && $diffInMins > 10);
                $isPreparingAlert = ($order->status === 'preparing' && $diffInMins > 30);
                $rowClass = $isPendingAlert ? 'row-alert-danger' : ($isPreparingAlert ? 'row-alert-warning' : '');
                
                $next = $statusNext[$order->status] ?? null;
            @endphp
            <tr class="{{ $rowClass }}">
                <td><strong>#{{ $order->id }}</strong></td>
                <td>{{ $order->restaurant->name ?? '—' }}</td>
                <td class="fw-600">{{ number_format($order->total_amount, 2, ',', '.') }} Kz</td>
                <td><span class="badge badge-{{ $order->status }}">{{ $statusLabels[$order->status] ?? $order->status }}</span></td>
                <td class="text-sm">
                    @if($isPendingAlert || $isPreparingAlert)
                        <span class="{{ $isPendingAlert ? 'text-alert-danger' : 'text-alert-warning' }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $order->created_at->diffForHumans() }}
                        </span>
                    @else
                        <span class="text-muted">{{ $order->created_at->diffForHumans() }}</span>
                    @endif
                </td>
                <td class="quick-actions">
                    @if($next)
                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" onsubmit="this.querySelector('button').disabled=true;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $next }}">
                        <button type="submit" class="btn btn-sm btn-primary">
                            @if($next == 'accepted') Aceitar
                            @elseif($next == 'preparing') Preparar
                            @elseif($next == 'ready') Pronto
                            @elseif($next == 'in_transit') Despachar
                            @elseif($next == 'delivered') Entregue
                            @else Mover para {{ $statusLabels[$next] }}
                            @endif
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-secondary">Ver</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="padding:16px 24px">{{ $orders->links() }}</div>
    @else
    <div class="table-empty">Nenhum pedido encontrado.</div>
    @endif
</div>
@endsection
