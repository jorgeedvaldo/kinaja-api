@extends('admin.layouts.app')

@section('page-title', 'Pedido #' . $order->id)
@section('title', 'Pedido #' . $order->id)

@php
    $statusLabels = ['pending'=>'Pendente','accepted'=>'Aceite','preparing'=>'Preparando','ready'=>'Pronto','in_transit'=>'Em Trânsito','delivered'=>'Entregue','cancelled'=>'Cancelado'];
    $statusNext = ['pending'=>'accepted','accepted'=>'preparing','preparing'=>'ready','ready'=>'in_transit','in_transit'=>'delivered'];
    $next = $statusNext[$order->status] ?? null;
@endphp

@section('content')
<div style="max-width:700px">
    <div class="order-detail-grid">
        <div class="detail-block">
            <div class="detail-block-label">Estado</div>
            <div class="detail-block-value"><span class="badge badge-{{ $order->status }}">{{ $statusLabels[$order->status] ?? $order->status }}</span></div>
        </div>
        <div class="detail-block">
            <div class="detail-block-label">Data</div>
            <div class="detail-block-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <div class="detail-block">
            <div class="detail-block-label">Cliente</div>
            <div class="detail-block-value">{{ $order->client->name ?? '—' }}</div>
        </div>
        <div class="detail-block">
            <div class="detail-block-label">Restaurante</div>
            <div class="detail-block-value">{{ $order->restaurant->name ?? '—' }}</div>
        </div>
        <div class="detail-block">
            <div class="detail-block-label">Total Produtos</div>
            <div class="detail-block-value">{{ number_format($order->total_amount, 2, ',', '.') }} Kz</div>
        </div>
        <div class="detail-block">
            <div class="detail-block-label">Taxa Entrega</div>
            <div class="detail-block-value">{{ number_format($order->delivery_fee, 2, ',', '.') }} Kz</div>
        </div>
        @if($order->delivery_address)
        <div class="detail-block" style="grid-column:1/-1">
            <div class="detail-block-label">Endereço</div>
            <div class="detail-block-value">{{ $order->delivery_address }}</div>
        </div>
        @endif
        @if($order->driver)
        <div class="detail-block">
            <div class="detail-block-label">Entregador</div>
            <div class="detail-block-value">{{ $order->driver->user->name ?? '—' }}</div>
        </div>
        @endif
    </div>

    <div class="section-title" style="font-weight:700;margin-bottom:8px">Itens ({{ $order->items->count() }})</div>
    <ul class="order-items-list">
        @foreach($order->items as $item)
        <li>
            <span>{{ $item->product->name ?? 'Produto #'.$item->product_id }} &times; {{ $item->quantity }}
                @if($item->notes) <em class="text-muted text-sm">({{ $item->notes }})</em> @endif
            </span>
            <span class="fw-600">{{ number_format($item->unit_price * $item->quantity, 2, ',', '.') }} Kz</span>
        </li>
        @endforeach
    </ul>

    @if(!in_array($order->status, ['delivered', 'cancelled']))
    <div class="status-actions" style="margin-top:20px">
        @if($next)
        <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" style="margin:0">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="{{ $next }}">
            <button type="submit" class="btn btn-success">Avançar → {{ $statusLabels[$next] }}</button>
        </form>
        @endif
        <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" style="margin:0" onsubmit="return confirm('Cancelar este pedido?')">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="cancelled">
            <button type="submit" class="btn btn-danger">Cancelar Pedido</button>
        </form>
    </div>
    @endif

    <div style="margin-top:24px">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">← Voltar</a>
    </div>
</div>
@endsection
