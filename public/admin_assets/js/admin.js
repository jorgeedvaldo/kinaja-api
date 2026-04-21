/* ═══════════════════════════════════════════════════════════════
   KinaJá Admin Panel — Application JS
   ═══════════════════════════════════════════════════════════════ */

const API = '/api';

// ─── State ────────────────────────────────────────────────────
const state = {
  token: localStorage.getItem('kj_token') || null,
  user: JSON.parse(localStorage.getItem('kj_user') || 'null'),
  currentPage: 'dashboard',
};

// ─── SVG Icons ────────────────────────────────────────────────
const ICONS = {
  dashboard: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>',
  orders: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><path d="M9 14l2 2 4-4"/></svg>',
  restaurants: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>',
  products: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
  categories: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>',
  users: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
  plus: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>',
  eye: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>',
  edit: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>',
  trash: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>',
};

const ROLE_LABELS = { admin: 'Administrador', client: 'Cliente', driver: 'Entregador', restaurant_owner: 'Dono Restaurante' };
const STATUS_LABELS = { pending: 'Pendente', accepted: 'Aceite', preparing: 'Preparando', ready: 'Pronto', in_transit: 'Em Trânsito', delivered: 'Entregue', cancelled: 'Cancelado' };
const STATUS_NEXT = { pending: 'accepted', accepted: 'preparing', preparing: 'ready', ready: 'in_transit', in_transit: 'delivered' };

// ─── API Helpers ──────────────────────────────────────────────
async function api(path, opts = {}) {
  const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json' };
  if (state.token) headers['Authorization'] = `Bearer ${state.token}`;
  const res = await fetch(`${API}${path}`, { headers, ...opts, body: opts.body ? JSON.stringify(opts.body) : undefined });
  if (res.status === 401) { logout(); throw new Error('Sessão expirada'); }
  if (res.status === 204) return null;
  const data = await res.json();
  if (!res.ok) throw new Error(data.message || 'Erro desconhecido');
  return data;
}
const get = (p) => api(p);
const post = (p, b) => api(p, { method: 'POST', body: b });
const put = (p, b) => api(p, { method: 'PUT', body: b });
const patch = (p, b) => api(p, { method: 'PATCH', body: b });
const del = (p) => api(p, { method: 'DELETE' });

// ─── UI Helpers ───────────────────────────────────────────────
const $ = (s) => document.querySelector(s);
const $$ = (s) => document.querySelectorAll(s);

function toast(msg, type = 'success') {
  const c = $('#toast-container');
  const t = document.createElement('div');
  t.className = `toast ${type}`;
  t.textContent = msg;
  c.appendChild(t);
  setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3000);
}

function showModal(title, bodyHTML) {
  $('#modal-title').textContent = title;
  $('#modal-body').innerHTML = bodyHTML;
  $('#modal-overlay').classList.remove('hidden');
}

function closeModal() { $('#modal-overlay').classList.add('hidden'); }

function badge(value, prefix = '') {
  return `<span class="badge badge-${prefix || value}">${STATUS_LABELS[value] || ROLE_LABELS[value] || value}</span>`;
}

function formatCurrency(v) {
  return Number(v).toLocaleString('pt-AO', { minimumFractionDigits: 2 }) + ' Kz';
}

function formatDate(d) {
  if (!d) return '—';
  return new Date(d).toLocaleString('pt-AO', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

// ─── Auth ─────────────────────────────────────────────────────
function login(user, token) {
  state.user = user;
  state.token = token;
  localStorage.setItem('kj_token', token);
  localStorage.setItem('kj_user', JSON.stringify(user));
}

function logout() {
  if (state.token) api('/logout', { method: 'POST' }).catch(() => {});
  state.user = null;
  state.token = null;
  localStorage.removeItem('kj_token');
  localStorage.removeItem('kj_user');
  showLogin();
}

function isAdmin() { return state.user?.role === 'admin'; }
function isOwner() { return state.user?.role === 'restaurant_owner'; }

// ─── Navigation ───────────────────────────────────────────────
const NAV_ITEMS = [
  { id: 'dashboard', label: 'Dashboard', icon: 'dashboard', roles: ['admin', 'restaurant_owner'] },
  { id: 'orders', label: 'Pedidos', icon: 'orders', roles: ['admin', 'restaurant_owner'] },
  { id: 'restaurants', label: 'Restaurantes', icon: 'restaurants', roles: ['admin', 'restaurant_owner'] },
  { id: 'products', label: 'Produtos', icon: 'products', roles: ['admin', 'restaurant_owner'] },
  { id: 'categories', label: 'Categorias', icon: 'categories', roles: ['admin', 'restaurant_owner'] },
  { id: 'users', label: 'Utilizadores', icon: 'users', roles: ['admin'] },
];

function buildSidebar() {
  const nav = $('#sidebar-nav');
  const role = state.user?.role || 'admin';
  nav.innerHTML = '<div class="nav-section-label">Menu</div>' +
    NAV_ITEMS.filter(i => i.roles.includes(role)).map(i =>
      `<a class="nav-item${state.currentPage === i.id ? ' active' : ''}" data-page="${i.id}">${ICONS[i.icon]}<span>${i.label}</span></a>`
    ).join('');
  nav.querySelectorAll('.nav-item').forEach(el => {
    el.addEventListener('click', () => navigate(el.dataset.page));
  });
  // User info
  if (state.user) {
    $('#user-name').textContent = state.user.name;
    $('#user-role').textContent = ROLE_LABELS[state.user.role] || state.user.role;
    $('#user-avatar').textContent = state.user.name.charAt(0).toUpperCase();
  }
}

function navigate(page) {
  state.currentPage = page;
  window.location.hash = page;
  buildSidebar();
  renderPage();
  // close mobile sidebar
  $('#sidebar').classList.remove('open');
}

// ─── Router ───────────────────────────────────────────────────
function renderPage() {
  const area = $('#content-area');
  area.innerHTML = '<div class="loading-center"><div class="spinner"></div><span>A carregar...</span></div>';
  const titles = { dashboard: 'Dashboard', orders: 'Pedidos', restaurants: 'Restaurantes', products: 'Produtos', categories: 'Categorias', users: 'Utilizadores' };
  $('#page-title').textContent = titles[state.currentPage] || 'Dashboard';

  switch (state.currentPage) {
    case 'dashboard': renderDashboard(area); break;
    case 'orders': renderOrders(area); break;
    case 'restaurants': renderRestaurants(area); break;
    case 'products': renderProducts(area); break;
    case 'categories': renderCategories(area); break;
    case 'users': renderUsers(area); break;
    default: renderDashboard(area);
  }
}

// ─── PAGES ────────────────────────────────────────────────────

// ——— Dashboard ———
async function renderDashboard(area) {
  try {
    const data = await get('/admin/dashboard');
    const s = data.stats;
    area.innerHTML = `
      <div class="stats-grid">
        <div class="stat-card"><div class="stat-card-header"><div class="stat-card-icon red">${ICONS.users}</div></div><div class="stat-card-value">${s.total_users}</div><div class="stat-card-label">Utilizadores</div></div>
        <div class="stat-card"><div class="stat-card-header"><div class="stat-card-icon yellow">${ICONS.restaurants}</div></div><div class="stat-card-value">${s.total_restaurants}</div><div class="stat-card-label">Restaurantes</div></div>
        <div class="stat-card"><div class="stat-card-header"><div class="stat-card-icon blue">${ICONS.orders}</div></div><div class="stat-card-value">${s.total_orders}</div><div class="stat-card-label">Total Pedidos</div></div>
        <div class="stat-card"><div class="stat-card-header"><div class="stat-card-icon green">${ICONS.products}</div></div><div class="stat-card-value">${formatCurrency(s.total_revenue)}</div><div class="stat-card-label">Receita (Entregues)</div></div>
      </div>
      <div class="table-card">
        <div class="table-header"><span class="table-title">Pedidos Recentes</span></div>
        <table><thead><tr><th>#</th><th>Cliente</th><th>Restaurante</th><th>Total</th><th>Estado</th><th>Data</th></tr></thead>
        <tbody>${(data.recent_orders || []).map(o => `
          <tr><td><strong>#${o.id}</strong></td><td>${o.client?.name || '—'}</td><td>${o.restaurant?.name || '—'}</td><td class="fw-600">${formatCurrency(o.total_amount)}</td><td>${badge(o.status)}</td><td class="text-sm text-muted">${formatDate(o.created_at)}</td></tr>
        `).join('')}</tbody></table>
      </div>`;
  } catch (e) {
    // Fallback for restaurant owners
    try {
      const orders = await get('/orders');
      const pending = orders.filter(o => o.status === 'pending').length;
      const active = orders.filter(o => !['delivered','cancelled'].includes(o.status)).length;
      const delivered = orders.filter(o => o.status === 'delivered').length;
      const revenue = orders.filter(o => o.status === 'delivered').reduce((s, o) => s + Number(o.total_amount), 0);
      area.innerHTML = `
        <div class="stats-grid">
          <div class="stat-card"><div class="stat-card-header"><div class="stat-card-icon yellow">${ICONS.orders}</div></div><div class="stat-card-value">${pending}</div><div class="stat-card-label">Pendentes</div></div>
          <div class="stat-card"><div class="stat-card-header"><div class="stat-card-icon blue">${ICONS.orders}</div></div><div class="stat-card-value">${active}</div><div class="stat-card-label">Activos</div></div>
          <div class="stat-card"><div class="stat-card-header"><div class="stat-card-icon green">${ICONS.orders}</div></div><div class="stat-card-value">${delivered}</div><div class="stat-card-label">Entregues</div></div>
          <div class="stat-card"><div class="stat-card-header"><div class="stat-card-icon red">${ICONS.products}</div></div><div class="stat-card-value">${formatCurrency(revenue)}</div><div class="stat-card-label">Receita</div></div>
        </div>
        <div class="table-card">
          <div class="table-header"><span class="table-title">Últimos Pedidos</span></div>
          <table><thead><tr><th>#</th><th>Restaurante</th><th>Total</th><th>Estado</th><th>Data</th></tr></thead>
          <tbody>${orders.slice(0, 10).map(o => `
            <tr style="cursor:pointer" onclick="viewOrder(${o.id})"><td><strong>#${o.id}</strong></td><td>${o.restaurant?.name || '—'}</td><td class="fw-600">${formatCurrency(o.total_amount)}</td><td>${badge(o.status)}</td><td class="text-sm text-muted">${formatDate(o.created_at)}</td></tr>
          `).join('')}</tbody></table>
        </div>`;
    } catch (e2) { area.innerHTML = `<p class="text-muted">Erro ao carregar: ${e2.message}</p>`; }
  }
}

// ——— Orders ———
async function renderOrders(area) {
  const orders = isAdmin() ? await get('/admin/orders') : await get('/orders');
  let filtered = [...orders];

  area.innerHTML = `
    <div class="table-card">
      <div class="table-header">
        <span class="table-title">Todos os Pedidos (${orders.length})</span>
        <div class="table-actions">
          <input type="text" class="table-search" id="order-search" placeholder="Pesquisar...">
          <select class="table-filter" id="order-filter">
            <option value="">Todos estados</option>
            <option value="pending">Pendente</option><option value="accepted">Aceite</option>
            <option value="preparing">Preparando</option><option value="ready">Pronto</option>
            <option value="in_transit">Em Trânsito</option><option value="delivered">Entregue</option>
            <option value="cancelled">Cancelado</option>
          </select>
        </div>
      </div>
      <div id="orders-table-body"></div>
    </div>`;

  function renderTable(list) {
    const body = $('#orders-table-body');
    if (!list.length) { body.innerHTML = '<div class="table-empty">Nenhum pedido encontrado.</div>'; return; }
    body.innerHTML = `<table><thead><tr><th>#</th><th>Cliente</th><th>Restaurante</th><th>Total</th><th>Taxa</th><th>Estado</th><th>Data</th><th>Ações</th></tr></thead>
    <tbody>${list.map(o => `
      <tr>
        <td><strong>#${o.id}</strong></td>
        <td>${o.client?.name || '—'}</td>
        <td>${o.restaurant?.name || '—'}</td>
        <td class="fw-600">${formatCurrency(o.total_amount)}</td>
        <td class="text-sm">${formatCurrency(o.delivery_fee)}</td>
        <td>${badge(o.status)}</td>
        <td class="text-sm text-muted">${formatDate(o.created_at)}</td>
        <td><button class="btn btn-sm btn-secondary" onclick="viewOrder(${o.id})">${ICONS.eye} Ver</button></td>
      </tr>
    `).join('')}</tbody></table>`;
  }

  renderTable(filtered);

  $('#order-filter').addEventListener('change', function () {
    const v = this.value;
    const q = ($('#order-search')?.value || '').toLowerCase();
    filtered = orders.filter(o => (!v || o.status === v) && (!q || (o.client?.name || '').toLowerCase().includes(q) || (o.restaurant?.name || '').toLowerCase().includes(q) || String(o.id).includes(q)));
    renderTable(filtered);
  });

  $('#order-search').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    const v = $('#order-filter').value;
    filtered = orders.filter(o => (!v || o.status === v) && (!q || (o.client?.name || '').toLowerCase().includes(q) || (o.restaurant?.name || '').toLowerCase().includes(q) || String(o.id).includes(q)));
    renderTable(filtered);
  });
}

// Global order viewer
window.viewOrder = async function (id) {
  try {
    const o = await get(`/orders/${id}`);
    const items = o.items || [];
    const nextStatus = STATUS_NEXT[o.status];
    showModal(`Pedido #${o.id}`, `
      <div class="order-detail-grid">
        <div class="detail-block"><div class="detail-block-label">Estado</div><div class="detail-block-value">${badge(o.status)}</div></div>
        <div class="detail-block"><div class="detail-block-label">Data</div><div class="detail-block-value">${formatDate(o.created_at)}</div></div>
        <div class="detail-block"><div class="detail-block-label">Cliente</div><div class="detail-block-value">${o.client?.name || '—'}</div></div>
        <div class="detail-block"><div class="detail-block-label">Restaurante</div><div class="detail-block-value">${o.restaurant?.name || '—'}</div></div>
        <div class="detail-block"><div class="detail-block-label">Total Produtos</div><div class="detail-block-value">${formatCurrency(o.total_amount)}</div></div>
        <div class="detail-block"><div class="detail-block-label">Taxa Entrega</div><div class="detail-block-value">${formatCurrency(o.delivery_fee)}</div></div>
        ${o.delivery_address ? `<div class="detail-block" style="grid-column:1/-1"><div class="detail-block-label">Endereço</div><div class="detail-block-value">${o.delivery_address}</div></div>` : ''}
        ${o.driver ? `<div class="detail-block"><div class="detail-block-label">Entregador</div><div class="detail-block-value">${o.driver.user?.name || '—'}</div></div>` : ''}
      </div>
      <div class="section-title">Itens (${items.length})</div>
      <ul class="order-items-list">${items.map(i => `
        <li><span>${i.product?.name || 'Produto #' + i.product_id} &times; ${i.quantity}${i.notes ? ` <em class="text-muted text-sm">(${i.notes})</em>` : ''}</span><span class="fw-600">${formatCurrency(i.unit_price * i.quantity)}</span></li>
      `).join('')}</ul>
      ${o.status !== 'delivered' && o.status !== 'cancelled' ? `
        <div class="status-actions">
          ${nextStatus ? `<button class="btn btn-success" onclick="updateOrderStatus(${o.id}, '${nextStatus}')">Avançar → ${STATUS_LABELS[nextStatus]}</button>` : ''}
          <button class="btn btn-danger" onclick="updateOrderStatus(${o.id}, 'cancelled')">Cancelar Pedido</button>
        </div>` : ''}
    `);
  } catch (e) { toast(e.message, 'error'); }
};

window.updateOrderStatus = async function (id, status) {
  try {
    if (status === 'cancelled') {
      await patch(`/orders/${id}/cancel`);
    } else {
      await patch(`/orders/${id}/status`, { status });
    }
    toast(`Pedido #${id} → ${STATUS_LABELS[status]}`);
    closeModal();
    renderPage();
  } catch (e) { toast(e.message, 'error'); }
};

// ——— Restaurants ———
async function renderRestaurants(area) {
  let restaurants = await get('/admin/restaurants');

  area.innerHTML = `
    <div class="table-card">
      <div class="table-header">
        <span class="table-title">Restaurantes (${restaurants.length})</span>
        <div class="table-actions">
          ${isOwner() ? `<button class="btn btn-primary" onclick="showRestaurantForm()">${ICONS.plus} Novo Restaurante</button>` : ''}
        </div>
      </div>
      <table><thead><tr><th>ID</th><th>Nome</th><th>Cozinha</th><th>Rating</th><th>Prep. (min)</th><th>Estado</th><th>Ações</th></tr></thead>
      <tbody>${restaurants.map(r => `
        <tr>
          <td>${r.id}</td>
          <td class="fw-600">${r.name}</td>
          <td>${r.cuisine_type || '—'}</td>
          <td>⭐ ${Number(r.rating).toFixed(1)}</td>
          <td>${r.prep_time_mins} min</td>
          <td>${badge(r.is_open ? 'open' : 'closed')}</td>
          <td class="flex gap-8">
            <button class="btn btn-sm btn-secondary" onclick="showRestaurantForm(${r.id})">${ICONS.edit}</button>
            <button class="btn btn-sm btn-danger" onclick="deleteRestaurant(${r.id})">${ICONS.trash}</button>
          </td>
        </tr>
      `).join('')}</tbody></table>
      ${!restaurants.length ? '<div class="table-empty">Nenhum restaurante encontrado.</div>' : ''}
    </div>`;
}

window.showRestaurantForm = async function (id) {
  let r = { name: '', cuisine_type: '', prep_time_mins: 30, is_open: false };
  if (id) {
    try { r = await get(`/restaurants/${id}`); } catch (e) { toast(e.message, 'error'); return; }
  }
  showModal(id ? 'Editar Restaurante' : 'Novo Restaurante', `
    <form id="restaurant-form">
      <div class="form-group"><label>Nome</label><input id="rf-name" value="${r.name}" required></div>
      <div class="form-group"><label>Tipo de Cozinha</label><input id="rf-cuisine" value="${r.cuisine_type || ''}"></div>
      <div class="form-group"><label>Tempo de Preparação (min)</label><input type="number" id="rf-prep" value="${r.prep_time_mins}" min="1"></div>
      <div class="form-group"><label>Imagem (URL)</label><input id="rf-image" value="${r.cover_image || ''}"></div>
      <div class="form-group flex items-center gap-8">
        <label class="toggle"><input type="checkbox" id="rf-open" ${r.is_open ? 'checked' : ''}><span class="toggle-slider"></span></label>
        <span>Aberto</span>
      </div>
      <div class="modal-footer" style="padding:16px 0 0">
        <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary">${id ? 'Salvar' : 'Criar'}</button>
      </div>
    </form>
  `);
  $('#restaurant-form').onsubmit = async (e) => {
    e.preventDefault();
    const body = { name: $('#rf-name').value, cuisine_type: $('#rf-cuisine').value, prep_time_mins: Number($('#rf-prep').value), cover_image: $('#rf-image').value, is_open: $('#rf-open').checked };
    try {
      if (id) await put(`/restaurants/${id}`, body);
      else await post('/restaurants', body);
      toast(id ? 'Restaurante atualizado!' : 'Restaurante criado!');
      closeModal();
      renderPage();
    } catch (e) { toast(e.message, 'error'); }
  };
};

window.deleteRestaurant = async function (id) {
  if (!confirm('Tem certeza que deseja apagar este restaurante?')) return;
  try { await del(`/restaurants/${id}`); toast('Restaurante apagado!'); renderPage(); } catch (e) { toast(e.message, 'error'); }
};

// ——— Products ———
async function renderProducts(area) {
  let restaurants = await get('/admin/restaurants');
  if (!restaurants.length) { area.innerHTML = '<div class="loading-center"><p class="text-muted">Nenhum restaurante encontrado. Crie um restaurante primeiro.</p></div>'; return; }

  area.innerHTML = `
    <div class="flex items-center justify-between mb-16 flex-wrap gap-8">
      <div class="flex items-center gap-8">
        <label class="fw-600 text-sm">Restaurante:</label>
        <select class="table-filter" id="prod-restaurant">${restaurants.map(r => `<option value="${r.id}">${r.name}</option>`).join('')}</select>
      </div>
      <button class="btn btn-primary" onclick="showProductForm()">${ICONS.plus} Novo Produto</button>
    </div>
    <div id="products-list"></div>`;

  async function loadProducts() {
    const rid = $('#prod-restaurant').value;
    const products = await get(`/restaurants/${rid}/products`);
    const div = $('#products-list');
    if (!products.length) { div.innerHTML = '<div class="table-card"><div class="table-empty">Nenhum produto neste restaurante.</div></div>'; return; }
    div.innerHTML = `<div class="table-card"><table><thead><tr><th>ID</th><th>Nome</th><th>Categoria</th><th>Preço</th><th>Disponível</th><th>Ações</th></tr></thead>
    <tbody>${products.map(p => `
      <tr><td>${p.id}</td><td class="fw-600">${p.name}</td><td>${p.category?.name || '—'}</td><td class="fw-600">${formatCurrency(p.price)}</td><td>${badge(p.is_available ? 'open' : 'closed')}</td>
      <td class="flex gap-8"><button class="btn btn-sm btn-secondary" onclick="showProductForm(${p.id})">${ICONS.edit}</button><button class="btn btn-sm btn-danger" onclick="deleteProduct(${p.id})">${ICONS.trash}</button></td></tr>
    `).join('')}</tbody></table></div>`;
  }

  loadProducts();
  $('#prod-restaurant').addEventListener('change', loadProducts);
}

window.showProductForm = async function (id) {
  const cats = await get('/categories');
  const rid = $('#prod-restaurant')?.value;
  let p = { name: '', description: '', price: '', category_id: cats[0]?.id, is_available: true, image: '' };
  if (id) {
    // Find in page or fetch
    const products = await get(`/restaurants/${rid}/products`);
    p = products.find(x => x.id === id) || p;
  }
  showModal(id ? 'Editar Produto' : 'Novo Produto', `
    <form id="product-form">
      <div class="form-group"><label>Nome</label><input id="pf-name" value="${p.name}" required></div>
      <div class="form-group"><label>Descrição</label><textarea id="pf-desc" rows="2">${p.description || ''}</textarea></div>
      <div class="form-group"><label>Preço (Kz)</label><input type="number" step="0.01" id="pf-price" value="${p.price}" required></div>
      <div class="form-group"><label>Categoria</label><select id="pf-cat">${cats.map(c => `<option value="${c.id}" ${c.id == p.category_id ? 'selected' : ''}>${c.name}</option>`).join('')}</select></div>
      <div class="form-group"><label>Imagem (URL)</label><input id="pf-image" value="${p.image || ''}"></div>
      <div class="form-group flex items-center gap-8"><label class="toggle"><input type="checkbox" id="pf-avail" ${p.is_available ? 'checked' : ''}><span class="toggle-slider"></span></label><span>Disponível</span></div>
      <div class="modal-footer" style="padding:16px 0 0"><button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button><button type="submit" class="btn btn-primary">${id ? 'Salvar' : 'Criar'}</button></div>
    </form>
  `);
  $('#product-form').onsubmit = async (e) => {
    e.preventDefault();
    const body = { name: $('#pf-name').value, description: $('#pf-desc').value, price: Number($('#pf-price').value), category_id: Number($('#pf-cat').value), image: $('#pf-image').value, is_available: $('#pf-avail').checked };
    try {
      if (id) await put(`/products/${id}`, body);
      else await post(`/restaurants/${rid}/products`, body);
      toast(id ? 'Produto atualizado!' : 'Produto criado!');
      closeModal();
      renderPage();
    } catch (e) { toast(e.message, 'error'); }
  };
};

window.deleteProduct = async function (id) {
  if (!confirm('Tem certeza?')) return;
  try { await del(`/products/${id}`); toast('Produto apagado!'); renderPage(); } catch (e) { toast(e.message, 'error'); }
};

// ——— Categories ———
async function renderCategories(area) {
  const cats = await get('/categories');
  area.innerHTML = `
    <div class="table-card">
      <div class="table-header">
        <span class="table-title">Categorias (${cats.length})</span>
        <div class="table-actions">
          <button class="btn btn-primary" onclick="showCategoryForm()">${ICONS.plus} Nova Categoria</button>
        </div>
      </div>
      <table><thead><tr><th>ID</th><th>Nome</th><th>Data Criação</th><th>Ações</th></tr></thead>
      <tbody>${cats.map(c => `
        <tr><td>${c.id}</td><td class="fw-600">${c.name}</td><td class="text-sm text-muted">${formatDate(c.created_at)}</td>
        <td class="flex gap-8"><button class="btn btn-sm btn-secondary" onclick="showCategoryForm(${c.id}, '${c.name.replace(/'/g, "\\'")}')">${ICONS.edit}</button><button class="btn btn-sm btn-danger" onclick="deleteCategory(${c.id})">${ICONS.trash}</button></td></tr>
      `).join('')}</tbody></table>
      ${!cats.length ? '<div class="table-empty">Nenhuma categoria.</div>' : ''}
    </div>`;
}

window.showCategoryForm = function (id, name) {
  showModal(id ? 'Editar Categoria' : 'Nova Categoria', `
    <form id="cat-form"><div class="form-group"><label>Nome</label><input id="cf-name" value="${name || ''}" required></div>
    <div class="modal-footer" style="padding:16px 0 0"><button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button><button type="submit" class="btn btn-primary">${id ? 'Salvar' : 'Criar'}</button></div></form>
  `);
  $('#cat-form').onsubmit = async (e) => {
    e.preventDefault();
    try {
      if (id) await put(`/categories/${id}`, { name: $('#cf-name').value });
      else await post('/categories', { name: $('#cf-name').value });
      toast(id ? 'Categoria atualizada!' : 'Categoria criada!');
      closeModal(); renderPage();
    } catch (e) { toast(e.message, 'error'); }
  };
};

window.deleteCategory = async function (id) {
  if (!confirm('Tem certeza?')) return;
  try { await del(`/categories/${id}`); toast('Categoria apagada!'); renderPage(); } catch (e) { toast(e.message, 'error'); }
};

// ——— Users (Admin only) ———
async function renderUsers(area) {
  if (!isAdmin()) { area.innerHTML = '<p class="text-muted">Sem permissão.</p>'; return; }
  const users = await get('/admin/users');
  area.innerHTML = `
    <div class="table-card">
      <div class="table-header">
        <span class="table-title">Utilizadores (${users.length})</span>
        <div class="table-actions">
          <input type="text" class="table-search" id="user-search" placeholder="Pesquisar...">
          <select class="table-filter" id="user-filter"><option value="">Todos</option><option value="admin">Admin</option><option value="client">Cliente</option><option value="driver">Entregador</option><option value="restaurant_owner">Dono Restaurante</option></select>
        </div>
      </div>
      <div id="users-table-body"></div>
    </div>`;

  function renderTable(list) {
    const body = $('#users-table-body');
    if (!list.length) { body.innerHTML = '<div class="table-empty">Nenhum utilizador encontrado.</div>'; return; }
    body.innerHTML = `<table><thead><tr><th>ID</th><th>Nome</th><th>Telefone</th><th>E-mail</th><th>Role</th><th>Registo</th></tr></thead>
    <tbody>${list.map(u => `
      <tr><td>${u.id}</td><td class="fw-600">${u.name}</td><td>${u.phone || '—'}</td><td class="text-sm">${u.email || '—'}</td><td>${badge(u.role)}</td><td class="text-sm text-muted">${formatDate(u.created_at)}</td></tr>
    `).join('')}</tbody></table>`;
  }

  renderTable(users);

  function applyFilters() {
    const q = ($('#user-search')?.value || '').toLowerCase();
    const r = $('#user-filter').value;
    renderTable(users.filter(u => (!r || u.role === r) && (!q || u.name.toLowerCase().includes(q) || (u.phone || '').includes(q) || (u.email || '').toLowerCase().includes(q))));
  }

  $('#user-filter').addEventListener('change', applyFilters);
  $('#user-search').addEventListener('input', applyFilters);
}

// ─── Screen management ───────────────────────────────────────
function showLogin() {
  $('#login-screen').classList.remove('hidden');
  $('#app-shell').classList.add('hidden');
}

function showApp() {
  $('#login-screen').classList.add('hidden');
  $('#app-shell').classList.remove('hidden');
  buildSidebar();
  const hash = window.location.hash.replace('#', '') || 'dashboard';
  state.currentPage = hash;
  navigate(hash);
}

// ─── Clock ────────────────────────────────────────────────────
function updateClock() {
  const el = $('#current-time');
  if (el) el.textContent = new Date().toLocaleString('pt-AO', { weekday: 'short', day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' });
}

// ─── Init ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  // Login form
  $('#login-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = $('#login-btn');
    const errEl = $('#login-error');
    errEl.classList.add('hidden');
    btn.querySelector('span').textContent = 'A entrar...';
    btn.querySelector('.btn-spinner').classList.remove('hidden');
    btn.disabled = true;

    try {
      const data = await api('/login', {
        method: 'POST',
        body: { identifier: $('#login-identifier').value, password: $('#login-password').value },
      });
      const user = data.user;
      if (user.role !== 'admin' && user.role !== 'restaurant_owner') {
        throw new Error('Acesso restrito a administradores e donos de restaurantes.');
      }
      login(user, data.token);
      showApp();
    } catch (err) {
      errEl.textContent = err.message;
      errEl.classList.remove('hidden');
    } finally {
      btn.querySelector('span').textContent = 'Entrar';
      btn.querySelector('.btn-spinner').classList.add('hidden');
      btn.disabled = false;
    }
  });

  // Logout
  $('#btn-logout').addEventListener('click', logout);

  // Modal close
  $('#modal-close').addEventListener('click', closeModal);
  $('#modal-overlay').addEventListener('click', (e) => { if (e.target === $('#modal-overlay')) closeModal(); });

  // Mobile menu toggle
  $('#menu-toggle').addEventListener('click', () => { $('#sidebar').classList.toggle('open'); });

  // Clock
  updateClock();
  setInterval(updateClock, 30000);

  // Init
  if (state.token && state.user && (state.user.role === 'admin' || state.user.role === 'restaurant_owner')) {
    showApp();
  } else {
    showLogin();
  }
});
