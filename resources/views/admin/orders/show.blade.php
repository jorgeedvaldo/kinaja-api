@extends('admin.layouts.app')

@section('page-title', 'Detalhe do Pedido #' . $order->id)
@section('title', 'Pedido #' . $order->id)

@php
    $statusLabels = ['pending'=>'Pendente','accepted'=>'Aceite','preparing'=>'Preparando','ready'=>'Pronto','in_transit'=>'Em Trânsito','delivered'=>'Entregue','cancelled'=>'Cancelado'];
    $statusNext = ['pending'=>'accepted','accepted'=>'preparing','preparing'=>'ready','ready'=>'in_transit','in_transit'=>'delivered'];
    $next = $statusNext[$order->status] ?? null;

    // Timeline progress calculation
    $flow = ['pending', 'accepted', 'preparing', 'ready', 'in_transit', 'delivered'];
    $currentIndex = array_search($order->status, $flow);
    if ($currentIndex === false) $currentIndex = -1; // cancelled
@endphp

@section('content')
<style>
    /* Status Colors copied from index for consistency */
    .badge-pending { background-color: #fef08a; color: #854d0e; }
    .badge-accepted { background-color: #bfdbfe; color: #1e40af; }
    .badge-preparing { background-color: #e9d5ff; color: #6b21a8; }
    .badge-ready { background-color: #bbf7d0; color: #166534; }
    .badge-in_transit { background-color: #fed7aa; color: #9a3412; }
    .badge-delivered { background-color: #86efac; color: #14532d; }
    .badge-cancelled { background-color: #fecaca; color: #991b1b; }

    .order-dashboard {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 900px) {
        .order-dashboard { grid-template-columns: 1fr; }
    }

    .info-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .info-card-header {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stepper {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 24px 0 16px 0;
    }
    
    .stepper::before {
        content: '';
        position: absolute;
        top: 14px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e2e8f0;
        z-index: 1;
    }

    .step {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 60px;
    }

    .step-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
        font-weight: bold;
        color: #94a3b8;
    }

    .step.active .step-circle {
        border-color: #3b82f6;
        color: #3b82f6;
        background: #eff6ff;
    }

    .step.completed .step-circle {
        background: #3b82f6;
        border-color: #3b82f6;
        color: #fff;
    }

    .step-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
        color: #64748b;
    }

    .step.active .step-label { color: #1e293b; }
    .step.completed .step-label { color: #3b82f6; }

    .step-time {
        font-size: 0.65rem;
        color: #94a3b8;
        margin-top: 4px;
        text-align: center;
    }

    .entity-row {
        display: flex;
        margin-bottom: 12px;
    }
    .entity-label {
        width: 100px;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .entity-value {
        flex: 1;
        font-weight: 600;
        font-size: 0.9rem;
        color: #1e293b;
    }

    .item-table {
        width: 100%;
        border-collapse: collapse;
    }
    .item-table th, .item-table td {
        padding: 12px 8px;
        border-bottom: 1px solid #f1f5f9;
        text-align: left;
    }
    .item-table th {
        color: #64748b;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
    }
    .item-table td { font-size: 0.9rem; }
</style>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary" style="margin-bottom: 16px;">← Voltar aos Pedidos</a>
        <div style="display: flex; align-items: center; gap: 12px;">
            <h1 style="font-size: 1.8rem; margin: 0;">Pedido #{{ $order->id }}</h1>
            <span class="badge badge-{{ $order->status }}" style="font-size: 1rem; padding: 4px 12px;">{{ $statusLabels[$order->status] ?? $order->status }}</span>
        </div>
        <div style="color: #64748b; margin-top: 8px; font-weight: 500;">
            Realizado em {{ $order->created_at->format('d/m/Y \à\s H:i') }} • Tempo Total: <span style="color:#0f172a;font-weight:700">{{ $totalTime }}</span>
        </div>
    </div>
    
    @if(!in_array($order->status, ['delivered', 'cancelled']))
    <div style="display: flex; gap: 12px;">
        <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="ajax-form" style="margin:0" data-confirm="Tem a certeza que deseja cancelar este pedido?">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="cancelled">
            <button type="submit" class="btn btn-danger" style="height: 44px;">Cancelar Pedido</button>
        </form>
        @if($next)
        <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="ajax-form" style="margin:0">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="{{ $next }}">
            <button type="submit" class="btn btn-primary" style="height: 44px; font-weight: 700; padding: 0 24px;">
                Avançar para {{ $statusLabels[$next] }} →
            </button>
        </form>
        @endif
    </div>
    @endif
</div>

<div class="order-dashboard">
    <!-- Left Column: Timeline & Items -->
    <div>
        @if($order->status !== 'cancelled')
        <div class="info-card">
            <div class="info-card-header">Progresso do Pedido</div>
            <div class="stepper">
                @foreach($flow as $index => $stepStatus)
                    @php
                        $isCompleted = $currentIndex > $index;
                        $isActive = $currentIndex === $index;
                    @endphp
                    <div class="step {{ $isCompleted ? 'completed' : '' }} {{ $isActive ? 'active' : '' }}">
                        <div class="step-circle">
                            @if($isCompleted)
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            @else 
                                {{ $index + 1 }} 
                            @endif
                        </div>
                        <div class="step-label">{{ $statusLabels[$stepStatus] }}</div>
                        
                        @if($stepStatus === 'pending')
                            <div class="step-time">{{ $order->created_at->format('H:i') }}</div>
                        @elseif($isActive)
                            <div class="step-time">{{ $order->updated_at->format('H:i') }}</div>
                        @elseif($isCompleted)
                            <div class="step-time">✓</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="info-card" style="background: #fef2f2; border-color: #fecaca;">
            <div class="info-card-header" style="color: #991b1b; border-bottom-color: #fecaca;">Pedido Cancelado</div>
            <p style="color: #7f1d1d; margin: 0; font-weight: 500;">Este pedido foi cancelado em {{ $order->updated_at->format('d/m/Y H:i') }}.</p>
        </div>
        @endif

        <div class="info-card">
            <div class="info-card-header">
                Itens do Pedido ({{ $order->items->sum('quantity') }})
            </div>
            <div style="overflow-x: auto;">
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th style="text-align: center;">Qtd</th>
                            <th style="text-align: right;">Preço Unit.</th>
                            <th style="text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $subtotal = 0; @endphp
                        @foreach($order->items as $item)
                        @php 
                            $itemTotal = $item->unit_price * $item->quantity;
                            $subtotal += $itemTotal;
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: #0f172a;">{{ $item->product->name ?? 'Produto #'.$item->product_id }}</div>
                                @if($item->notes)
                                <div style="font-size: 0.8rem; color: #64748b; margin-top: 4px; padding: 4px 8px; background: #f8fafc; border-radius: 4px; display: inline-block;">
                                    <strong>Obs:</strong> {{ $item->notes }}
                                </div>
                                @endif
                            </td>
                            <td style="text-align: center; font-weight: 600;">{{ $item->quantity }}x</td>
                            <td style="text-align: right; color: #64748b;">{{ number_format($item->unit_price, 2, ',', '.') }} Kz</td>
                            <td style="text-align: right; font-weight: 600;">{{ number_format($itemTotal, 2, ',', '.') }} Kz</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 24px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; color: #64748b;">
                    <span>Subtotal Produtos</span>
                    <span style="font-weight: 500; color: #1e293b;">{{ number_format($subtotal, 2, ',', '.') }} Kz</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 16px; color: #64748b;">
                    <span>Taxa de Entrega</span>
                    <span style="font-weight: 500; color: #1e293b;">{{ number_format($order->delivery_fee, 2, ',', '.') }} Kz</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; font-weight: 800; font-size: 1.25rem; color: #0f172a; padding-top: 16px; border-top: 2px dashed #cbd5e1;">
                    <span>Total a Pagar</span>
                    <span style="color: #3b82f6;">{{ number_format($order->total_amount, 2, ',', '.') }} Kz</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Entities -->
    <div>
        <div class="info-card">
            <div class="info-card-header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Cliente
            </div>
            <div class="entity-row">
                <div class="entity-label">Nome</div>
                <div class="entity-value">{{ $order->client->name ?? '—' }}</div>
            </div>
            <div class="entity-row">
                <div class="entity-label">Telefone</div>
                <div class="entity-value">{{ $order->client->phone ?? '—' }}</div>
            </div>
            @if($order->delivery_address)
            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
                <div class="entity-label" style="margin-bottom: 8px;">Morada de Entrega</div>
                <div class="entity-value" style="line-height: 1.5; font-size: 0.95rem;">{{ $order->delivery_address }}</div>
            </div>
            @endif
        </div>

        <div class="info-card">
            <div class="info-card-header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
                Restaurante
            </div>
            <div class="entity-row">
                <div class="entity-label">Nome</div>
                <div class="entity-value">{{ $order->restaurant->name ?? '—' }}</div>
            </div>
            <div class="entity-row" style="margin-bottom:0">
                <div class="entity-label">Contacto</div>
                <div class="entity-value">{{ $order->restaurant->phone ?? '—' }}</div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-card-header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                Entregador
            </div>
            @if($order->driver)
            <div class="entity-row">
                <div class="entity-label">Nome</div>
                <div class="entity-value">{{ $order->driver->user->name ?? '—' }}</div>
            </div>
            <div class="entity-row" style="margin-bottom:0">
                <div class="entity-label">Telefone</div>
                <div class="entity-value">{{ $order->driver->user->phone ?? '—' }}</div>
            </div>
            @else
            <div style="padding: 16px; background: #f8fafc; border-radius: 8px; border: 1px dashed #cbd5e1; text-align: center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="margin: 0 auto 8px auto;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div style="color: #64748b; font-size: 0.9rem; font-weight: 500;">Nenhum entregador atribuído</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
