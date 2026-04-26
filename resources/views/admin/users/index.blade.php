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
<div class="table-card" style="margin-bottom: 24px; padding: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <span class="table-title" style="margin: 0;">Utilizadores ({{ $users->total() }})</span>
    </div>
    <form method="GET" action="{{ route('admin.users.index') }}" style="margin:0; background: #f8fafc; padding: 16px; border-radius: 8px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end;">
        <div>
            <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">Perfil (Role)</label>
            <select name="role" class="table-filter" style="width:100%">
                <option value="">Todos</option>
                @foreach($roleLabels as $key => $label)
                <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">Status</label>
            <select name="status" class="table-filter" style="width:100%">
                <option value="">Todos</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspenso</option>
                <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banido</option>
            </select>
        </div>
        <div>
            <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">Criado (De)</label>
            <input type="date" name="created_from" value="{{ request('created_from') }}" class="table-filter" style="width:100%">
        </div>
        <div>
            <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">Criado (Até)</label>
            <input type="date" name="created_to" value="{{ request('created_to') }}" class="table-filter" style="width:100%">
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="flex:1">Filtrar</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Limpar</a>
        </div>
    </form>
</div>

<div class="table-card">
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
                        <div style="display:inline-flex; gap: 4px;">
                            @if($u->status !== 'active')
                                <form action="{{ route('admin.users.updateStatus', $u) }}" method="POST" class="ajax-form" style="margin:0">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="btn btn-primary" style="padding: 4px 8px; font-size: 0.8rem; min-height: 28px;">Ativar</button>
                                </form>
                            @endif

                            @if($u->status === 'active')
                                <form action="{{ route('admin.users.updateStatus', $u) }}" method="POST" class="ajax-form" style="margin:0">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="suspended">
                                    <button type="submit" class="btn btn-secondary" style="padding: 4px 8px; font-size: 0.8rem; min-height: 28px;">Suspender</button>
                                </form>
                            @endif

                            @if($u->status !== 'banned')
                                <form action="{{ route('admin.users.updateStatus', $u) }}" method="POST" class="ajax-form" style="margin:0" data-confirm="Tem a certeza que pretende banir o utilizador {{ $u->name }}?">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="banned">
                                    <button type="submit" class="btn btn-secondary" style="padding: 4px 8px; font-size: 0.8rem; min-height: 28px; background: #fee2e2; border-color: #fca5a5; color: #991b1b;">Banir</button>
                                </form>
                            @endif
                        </div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="padding:16px 24px">{{ $users->links() }}</div>
    @else
    <div class="table-empty">Nenhum utilizador encontrado.</div>
    @endif
</div>
@endsection
