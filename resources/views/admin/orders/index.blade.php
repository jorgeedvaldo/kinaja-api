@extends('admin.layouts.app')

@section('page-title', 'Pedidos')
@section('title', 'Pedidos')

@php
    $statusLabels = ['pending'=>'Pendente','accepted'=>'Aceite','preparing'=>'Preparando','ready'=>'Pronto','in_transit'=>'Em Trânsito','delivered'=>'Entregue','cancelled'=>'Cancelado'];
@endphp

@section('content')
<div class="table-card">
    <div class="table-header">
        <span class="table-title">Pedidos</span>
        <div class="table-actions">
            <form method="GET" action="{{ route('admin.orders.index') }}" style="margin:0">
                <select name="status" class="table-filter" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    @foreach($statusLabels as $key => $label)
                    <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    @if($orders->count())
    <table>
        <thead><tr><th>#</th><th>Restaurante</th><th>Total</th><th>Estado</th><th>Data</th><th></th></tr></thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td><strong>#{{ $order->id }}</strong></td>
                <td>{{ $order->restaurant->name ?? '—' }}</td>
                <td class="fw-600">{{ number_format($order->total_amount, 2, ',', '.') }} Kz</td>
                <td><span class="badge badge-{{ $order->status }}">{{ $statusLabels[$order->status] ?? $order->status }}</span></td>
                <td class="text-sm text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-secondary">Ver</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="padding:16px 24px">{{ $orders->appends(['status' => $status])->links() }}</div>
    @else
    <div class="table-empty">Nenhum pedido encontrado.</div>
    @endif
</div>
@endsection
