@extends('admin.layouts.app')

@section('page-title', 'Candidaturas: Restaurantes')
@section('title', 'Candidaturas Restaurantes')

@php
    $statusLabels = [
        'pending' => ['label' => 'Pendente', 'color' => 'warning'],
        'approved' => ['label' => 'Aprovado', 'color' => 'success'],
        'rejected' => ['label' => 'Rejeitado', 'color' => 'danger'],
    ];
@endphp

@section('content')
<div class="table-card" style="margin-bottom: 24px; padding: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <span class="table-title" style="margin: 0;">Candidaturas de Restaurantes ({{ $applications->total() }})</span>
    </div>
    <form method="GET" action="{{ route('admin.restaurant_applications.index') }}" style="margin:0; background: #f8fafc; padding: 16px; border-radius: 8px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end;">
        <div>
            <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">Status</label>
            <select name="status" class="table-filter" style="width:100%">
                <option value="">Todos</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprovado</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeitado</option>
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="flex:1">Filtrar</button>
            <a href="{{ route('admin.restaurant_applications.index') }}" class="btn btn-secondary">Limpar</a>
        </div>
    </form>
</div>

<div class="table-card">
    @if($applications->count())
    <table>
        <thead><tr><th>ID</th><th>Restaurante</th><th>Telefone</th><th>NIF</th><th>Status</th><th>Data</th><th>Ações</th></tr></thead>
        <tbody>
            @foreach($applications as $app)
            <tr class="{{ $app->status === 'pending' ? 'bg-warning-light' : '' }}" style="{{ $app->status === 'pending' ? 'background: #fffbeb;' : '' }}">
                <td>{{ $app->id }}</td>
                <td class="fw-600">{{ $app->name }}</td>
                <td>{{ $app->phone }}</td>
                <td>{{ $app->nif ?? '—' }}</td>
                <td>
                    @php $sInfo = $statusLabels[$app->status] ?? ['label' => $app->status, 'color' => 'secondary']; @endphp
                    <span class="badge badge-{{ $sInfo['color'] }}">{{ $sInfo['label'] }}</span>
                </td>
                <td class="text-sm text-muted">{{ $app->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.restaurant_applications.show', $app) }}" class="btn btn-sm btn-outline">Ver Detalhes</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="padding:16px 24px">{{ $applications->links() }}</div>
    @else
    <div class="table-empty">Nenhuma candidatura encontrada.</div>
    @endif
</div>
@endsection
