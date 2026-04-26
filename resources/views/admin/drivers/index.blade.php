@extends('admin.layouts.app')

@section('page-title', 'Motoristas')
@section('title', 'Motoristas')

@section('content')
<style>
    .badge-online { background-color: #dcfce7; color: #166534; }
    .badge-offline { background-color: #f1f5f9; color: #475569; }
    .badge-busy { background-color: #fee2e2; color: #991b1b; }
    .badge-free { background-color: #e0f2fe; color: #0369a1; }
    
    #map {
        height: 400px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
        z-index: 1; /* Keep leaflet beneath modals/dropdowns */
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="table-card" style="margin-bottom: 24px; padding: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <span class="table-title" style="margin: 0;">Filtros de Motoristas</span>
    </div>
    <form method="GET" action="{{ route('admin.drivers.index') }}" style="margin:0; background: #f8fafc; padding: 16px; border-radius: 8px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end;">
        <div>
            <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">Estado Online/Offline</label>
            <select name="status" class="table-filter" style="width:100%">
                <option value="">Todos</option>
                <option value="online" {{ request('status') === 'online' ? 'selected' : '' }}>Online</option>
                <option value="offline" {{ request('status') === 'offline' ? 'selected' : '' }}>Offline</option>
            </select>
        </div>
        <div>
            <label class="text-sm fw-600 text-muted" style="display:block; margin-bottom:4px">Disponibilidade</label>
            <select name="availability" class="table-filter" style="width:100%">
                <option value="">Todas</option>
                <option value="free" {{ request('availability') === 'free' ? 'selected' : '' }}>Livres (Sem Pedido)</option>
                <option value="busy" {{ request('availability') === 'busy' ? 'selected' : '' }}>Ocupados (Com Pedido)</option>
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="flex:1">Filtrar</button>
            <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary">Limpar</a>
        </div>
    </form>
</div>

<!-- Map Area -->
<div class="table-card" style="margin-bottom: 24px; padding: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <span class="table-title" style="margin: 0;">Localização em Tempo Real (Online)</span>
        <span class="text-sm text-muted" id="map-status">A aguardar mapa...</span>
    </div>
    <div id="map"></div>
</div>

<div class="table-card">
    <div class="table-header">
        <span class="table-title">Lista de Motoristas ({{ $drivers->total() }})</span>
    </div>
    @if($drivers->count())
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Contacto</th>
                <th>Veículo</th>
                <th>Status</th>
                <th>Carga Atual</th>
                <th>Última Coord.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($drivers as $driver)
            @php
                $activeOrders = $driver->orders->whereNotIn('status', ['delivered', 'cancelled']);
                $isBusy = $activeOrders->count() > 0;
            @endphp
            <tr>
                <td><strong>#{{ $driver->id }}</strong></td>
                <td class="fw-600">{{ $driver->user->name ?? '—' }}</td>
                <td>{{ $driver->user->phone ?? '—' }}</td>
                <td>
                    <div style="font-size:0.9rem">{{ ucfirst($driver->vehicle_type ?? 'N/A') }}</div>
                    <div class="text-muted text-sm">{{ $driver->license_plate ?? 'N/A' }}</div>
                </td>
                <td>
                    @if($driver->is_online)
                        <span class="badge badge-online">Online</span>
                    @else
                        <span class="badge badge-offline">Offline</span>
                    @endif
                </td>
                <td>
                    @if($driver->is_online)
                        @if($isBusy)
                            <span class="badge badge-busy">Ocupado</span>
                            <div class="text-sm" style="margin-top:4px">
                                @foreach($activeOrders as $o)
                                    <a href="{{ route('admin.orders.show', $o->id) }}">Pedido #{{ $o->id }}</a><br>
                                @endforeach
                            </div>
                        @else
                            <span class="badge badge-free">Livre</span>
                        @endif
                    @else
                        <span class="text-muted text-sm">—</span>
                    @endif
                </td>
                <td class="text-sm text-muted">
                    @if($driver->current_lat && $driver->current_lng)
                        <a href="https://www.google.com/maps?q={{ $driver->current_lat }},{{ $driver->current_lng }}" target="_blank" style="color:#3b82f6; text-decoration:none;">
                            Ver no Maps ↗
                        </a>
                    @else
                        Sem Dados
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="padding:16px 24px">{{ $drivers->links() }}</div>
    @else
    <div class="table-empty">Nenhum motorista encontrado.</div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Luanda center as default fallback
    const map = L.map('map').setView([-8.814656, 13.230175], 11);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let markers = {};

    function updateLocations() {
        fetch('{{ route("admin.drivers.locations") }}')
            .then(response => response.json())
            .then(data => {
                const statusEl = document.getElementById('map-status');
                statusEl.textContent = 'Atualizado agora: ' + data.length + ' online';

                // Remove old markers that went offline
                const newIds = data.map(d => d.id);
                for (let id in markers) {
                    if (!newIds.includes(parseInt(id))) {
                        map.removeLayer(markers[id]);
                        delete markers[id];
                    }
                }

                data.forEach(driver => {
                    const latlng = [driver.lat, driver.lng];
                    const color = driver.status === 'busy' ? '#ef4444' : '#10b981'; // red if busy, green if free
                    const html = `<div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.4);"></div>`;
                    
                    const icon = L.divIcon({
                        className: 'custom-div-icon',
                        html: html,
                        iconSize: [14, 14],
                        iconAnchor: [7, 7]
                    });

                    if (markers[driver.id]) {
                        markers[driver.id].setLatLng(latlng);
                        markers[driver.id].setIcon(icon);
                    } else {
                        const marker = L.marker(latlng, {icon: icon})
                            .bindPopup(`<b>${driver.name}</b><br>Status: ${driver.status === 'busy' ? 'Ocupado' : 'Livre'}`)
                            .addTo(map);
                        markers[driver.id] = marker;
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching driver locations:', error);
                document.getElementById('map-status').textContent = 'Erro ao atualizar';
            });
    }

    // Initial load and interval
    updateLocations();
    setInterval(updateLocations, 10000); // 10 seconds
});
</script>
@endsection
