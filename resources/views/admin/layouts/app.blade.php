<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Painel') — KinaJá</title>
    <meta name="description" content="Painel de administração Kinajá para gestão de restaurantes, pedidos e utilizadores.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin_assets/css/admin.css') }}">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="app-shell">
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="logo-kina sm"><span>KINA</span></div>
                <div class="logo-ja sm"><span>JÁ</span></div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-label">Menu</div>
            <a class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                <span>Dashboard</span>
            </a>
            <a class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><path d="M9 14l2 2 4-4"/></svg>
                <span>Pedidos</span>
            </a>
            <a class="nav-item {{ request()->routeIs('admin.restaurants.*') ? 'active' : '' }}" href="{{ route('admin.restaurants.index') }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
                <span>Restaurantes</span>
            </a>
            <a class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <span>Produtos</span>
            </a>
            <a class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                <span>Categorias</span>
            </a>
            @if(auth()->user()->isAdmin())
            <a class="nav-item {{ request()->routeIs('admin.drivers.*') ? 'active' : '' }}" href="{{ route('admin.drivers.index') }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                <span>Motoristas</span>
            </a>
            <a class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>Utilizadores</span>
            </a>
            @endif
        </nav>
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-role">{{ auth()->user()->role === 'admin' ? 'Administrador' : 'Dono Restaurante' }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="btn-logout" title="Sair">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="main-content">
        <header class="top-bar">
            <button class="menu-toggle" id="menu-toggle">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            <div class="top-bar-right">
                <span class="current-time" id="current-time"></span>
            </div>
        </header>
        <div class="content-area">
            @yield('content')
        </div>
    </main>
</div>

{{-- Minimal JS: mobile menu toggle + clock --}}
<script>
document.getElementById('menu-toggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('open');
});
(function updateClock() {
    var el = document.getElementById('current-time');
    if (el) el.textContent = new Date().toLocaleString('pt-AO', { weekday: 'short', day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' });
    setTimeout(updateClock, 30000);
})();

// SweetAlert2 Setup & Interceptors
const Toast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

@if(session('success')) Toast.fire({ icon: 'success', title: "{{ session('success') }}" }); @endif
@if(session('error')) Toast.fire({ icon: 'error', title: "{{ session('error') }}" }); @endif
@if($errors->any()) Toast.fire({ icon: 'error', title: "Erro de validação!" }); @endif

document.addEventListener('submit', async function(e) {
    const form = e.target;

    // 1. Confirmation Interceptor
    if (form.hasAttribute('data-confirm')) {
        e.preventDefault();
        const msg = form.getAttribute('data-confirm');
        const result = await Swal.fire({
            title: 'Atenção', text: msg, icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#EB2835', cancelButtonColor: '#6B7280',
            confirmButtonText: 'Sim, confirmar!', cancelButtonText: 'Cancelar'
        });
        
        if (result.isConfirmed) {
            form.removeAttribute('data-confirm'); // prevent infinite loop
            // If it's also an ajax form, let the next block handle it
            if(!form.classList.contains('ajax-form')) {
                form.submit();
            } else {
                form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
            }
        }
        return;
    }

    // 2. Ajax Form Interceptor (Micro-SPA)
    if (form.classList.contains('ajax-form') && !form.hasAttribute('data-confirm')) {
        e.preventDefault();
        let btn = form.querySelector('button[type="submit"]');
        let originalText = btn ? btn.innerHTML : '';
        
        if (btn) {
            btn.innerHTML = `<svg style="animation: spin 1s linear infinite; width:16px; height:16px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processando...`;
            btn.disabled = true;
        }

        try {
            let response = await fetch(form.action, {
                method: form.method || 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            if (response.ok || response.redirected) {
                // Fetch the new HTML and replace the content area to prevent reload flash
                let text = await response.text();
                let doc = new DOMParser().parseFromString(text, 'text/html');
                document.querySelector('.content-area').innerHTML = doc.querySelector('.content-area').innerHTML;
                
                // Manually fire the toast since we bypassed full load
                Toast.fire({ icon: 'success', title: 'Atualizado com sucesso!' });
            }
        } catch (error) {
            Toast.fire({ icon: 'error', title: 'Ocorreu um erro.' });
            if (btn) { btn.innerHTML = originalText; btn.disabled = false; }
        }
    }
});
</script>
<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
</body>
</html>
