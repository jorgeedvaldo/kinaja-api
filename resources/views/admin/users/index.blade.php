@extends('admin.layouts.app')

@section('page-title', 'Utilizadores')
@section('title', 'Utilizadores')

@php
    $roleLabels = ['admin'=>'Administrador','client'=>'Cliente','driver'=>'Entregador','restaurant_owner'=>'Dono Restaurante'];
    $statusLabels = [
        'active' => ['label' => 'Ativo', 'color' => 'success'],
        'suspended' => ['label' => 'Suspenso', 'color' => 'warning'],
        'banned' => ['label' => 'Banido', 'color' => 'danger'],
    ];
@endphp

@section('content')
<div class="table-card">
    <div class="table-header">
        <span class="table-title">Utilizadores ({{ $users->total() }})</span>
        <div class="table-actions">
            <form method="GET" action="{{ route('admin.users.index') }}" style="margin:0">
                <select name="role" class="table-filter" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    @foreach($roleLabels as $key => $label)
                    <option value="{{ $key }}" {{ $role === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    @if($users->count())
    <table>
        <thead><tr><th>ID</th><th>Nome</th><th>Telefone</th><th>E-mail</th><th>Role</th><th>Status</th><th>Registo</th><th>Ações</th></tr></thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td class="fw-600">{{ $u->name }}</td>
                <td>{{ $u->phone ?? '—' }}</td>
                <td class="text-sm">{{ $u->email ?? '—' }}</td>
                <td><span class="badge badge-{{ $u->role }}">{{ $roleLabels[$u->role] ?? $u->role }}</span></td>
                <td>
                    @php $sInfo = $statusLabels[$u->status ?? 'active'] ?? ['label' => $u->status, 'color' => 'secondary']; @endphp
                    <span class="badge badge-{{ $sInfo['color'] }}">{{ $sInfo['label'] }}</span>
                    @if($u->status_updated_by)
                        <div class="text-sm text-muted mt-1" style="font-size:0.75rem">por Admin #{{ $u->status_updated_by }}</div>
                    @endif
                </td>
                <td class="text-sm text-muted">{{ $u->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if(!$u->isAdmin())
                        <form action="{{ route('admin.users.updateStatus', $u) }}" method="POST" style="display:inline-flex; gap: 4px;">
                            @csrf
                            @method('PATCH')
                            
                            @if($u->status !== 'active')
                                <button type="submit" name="status" value="active" class="btn btn-primary" style="padding: 4px 8px; font-size: 0.8rem; min-height: 28px;">Ativar</button>
                            @endif

                            @if($u->status === 'active')
                                <button type="submit" name="status" value="suspended" class="btn btn-secondary" style="padding: 4px 8px; font-size: 0.8rem; min-height: 28px;">Suspender</button>
                            @endif

                            @if($u->status !== 'banned')
                                <button type="submit" name="status" value="banned" class="btn btn-secondary" style="padding: 4px 8px; font-size: 0.8rem; min-height: 28px; background: #fee2e2; border-color: #fca5a5; color: #991b1b;">Banir</button>
                            @endif
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="padding:16px 24px">{{ $users->appends(['role' => $role])->links() }}</div>
    @else
    <div class="table-empty">Nenhum utilizador encontrado.</div>
    @endif
</div>
@endsection
