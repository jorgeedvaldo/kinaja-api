<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KinaJá — Painel de Administração</title>
    <meta name="description" content="Painel de administração Kinajá para gestão de restaurantes, pedidos e utilizadores.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin_assets/css/admin.css') }}">
</head>
<body>

<!-- ═══════ LOGIN SCREEN ═══════ -->
<div id="login-screen" class="login-screen">
    <div class="login-bg-circle1"></div>
    <div class="login-bg-circle2"></div>
    <div class="login-card">
        <div class="login-logo">
            <div class="logo-kina"><span>KINA</span></div>
            <div class="logo-ja"><span>JÁ</span></div>
        </div>
        <p class="login-subtitle">Painel de Administração</p>
        <form id="login-form" autocomplete="off">
            <div class="form-group">
                <label for="login-identifier">E-mail ou Telefone</label>
                <input type="text" id="login-identifier" placeholder="admin@kinaja.co" required>
            </div>
            <div class="form-group">
                <label for="login-password">Senha</label>
                <input type="password" id="login-password" placeholder="••••••••" required>
            </div>
            <div id="login-error" class="login-error hidden"></div>
            <button type="submit" class="btn-login" id="login-btn">
                <span>Entrar</span>
                <svg class="btn-spinner hidden" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-dasharray="32" stroke-dashoffset="32"><animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite"/></circle></svg>
            </button>
        </form>
        <p class="login-footer">Rápido. Gostoso.</p>
    </div>
</div>

<!-- ═══════ MAIN APP SHELL ═══════ -->
<div id="app-shell" class="app-shell hidden">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="logo-kina sm"><span>KINA</span></div>
                <div class="logo-ja sm"><span>JÁ</span></div>
            </div>
        </div>
        <nav class="sidebar-nav" id="sidebar-nav">
            <!-- Populated by JS -->
        </nav>
        <div class="sidebar-footer">
            <div class="sidebar-user" id="sidebar-user">
                <div class="user-avatar" id="user-avatar">A</div>
                <div class="user-info">
                    <span class="user-name" id="user-name">Admin</span>
                    <span class="user-role" id="user-role">Administrador</span>
                </div>
            </div>
            <button class="btn-logout" id="btn-logout" title="Sair">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="top-bar">
            <button class="menu-toggle" id="menu-toggle">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <h1 class="page-title" id="page-title">Dashboard</h1>
            <div class="top-bar-right">
                <span class="current-time" id="current-time"></span>
            </div>
        </header>
        <div class="content-area" id="content-area">
            <!-- Populated by JS -->
        </div>
    </main>
</div>

<!-- ═══════ MODAL ═══════ -->
<div class="modal-overlay hidden" id="modal-overlay">
    <div class="modal" id="modal">
        <div class="modal-header">
            <h2 class="modal-title" id="modal-title">Modal</h2>
            <button class="modal-close" id="modal-close">&times;</button>
        </div>
        <div class="modal-body" id="modal-body"></div>
    </div>
</div>

<!-- Toast -->
<div class="toast-container" id="toast-container"></div>

<script src="{{ asset('admin_assets/js/admin.js') }}"></script>
</body>
</html>
