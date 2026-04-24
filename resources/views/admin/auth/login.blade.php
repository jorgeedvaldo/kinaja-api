@extends('admin.layouts.guest')

@section('content')
<div class="login-screen">
    <div class="login-bg-circle1"></div>
    <div class="login-bg-circle2"></div>
    <div class="login-card">
        <div class="login-logo">
            <div class="logo-kina"><span>KINA</span></div>
            <div class="logo-ja"><span>JÁ</span></div>
        </div>
        <p class="login-subtitle">Painel de Administração</p>
        <form method="POST" action="{{ route('admin.login.submit') }}" autocomplete="off">
            @csrf
            <div class="form-group">
                <label for="login-identifier">E-mail ou Telefone</label>
                <input type="text" id="login-identifier" name="identifier" value="{{ old('identifier') }}" placeholder="admin@kinaja.co" required autofocus>
            </div>
            <div class="form-group">
                <label for="login-password">Senha</label>
                <input type="password" id="login-password" name="password" placeholder="••••••••" required>
            </div>
            <div class="form-group flex items-center gap-8">
                <label class="toggle">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
                <span style="font-size:13px;color:#6B7280;">Lembrar-me</span>
            </div>
            @if($errors->any())
                <div class="login-error">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif
            <button type="submit" class="btn-login">
                <span>Entrar</span>
            </button>
        </form>
        <p class="login-footer">Rápido. Gostoso.</p>
    </div>
</div>
@endsection
