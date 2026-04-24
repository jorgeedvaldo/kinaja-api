@extends('admin.layouts.app')

@section('page-title', 'Utilizadores')
@section('title', 'Utilizadores')

@php
    $roleLabels = ['admin'=>'Administrador','client'=>'Cliente','driver'=>'Entregador','restaurant_owner'=>'Dono Restaurante'];
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
        <thead><tr><th>ID</th><th>Nome</th><th>Telefone</th><th>E-mail</th><th>Role</th><th>Registo</th></tr></thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td class="fw-600">{{ $u->name }}</td>
                <td>{{ $u->phone ?? '—' }}</td>
                <td class="text-sm">{{ $u->email ?? '—' }}</td>
                <td><span class="badge badge-{{ $u->role }}">{{ $roleLabels[$u->role] ?? $u->role }}</span></td>
                <td class="text-sm text-muted">{{ $u->created_at->format('d/m/Y H:i') }}</td>
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
