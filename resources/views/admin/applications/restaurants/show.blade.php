@extends('admin.layouts.app')

@section('page-title', 'Detalhe Candidatura #' . $application->id)
@section('title', 'Candidatura Restaurante')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <a href="{{ route('admin.restaurant_applications.index') }}" class="btn btn-secondary">← Voltar</a>
    
    @if($application->status === 'pending')
    <div style="display: flex; gap: 8px;">
        <form method="POST" action="{{ route('admin.restaurant_applications.updateStatus', $application) }}" class="ajax-form" style="margin:0">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="rejected">
            <input type="hidden" name="rejection_reason" class="rejection-reason-input" value="">
            <button type="button" onclick="promptReject(this)" class="btn btn-danger">Rejeitar</button>
        </form>
        <form method="POST" action="{{ route('admin.restaurant_applications.updateStatus', $application) }}" class="ajax-form" style="margin:0" data-confirm="Tem a certeza que deseja aprovar este restaurante?">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="approved">
            @php
                $docsMissing = !$application->business_license_path;
            @endphp
            <button type="submit" class="btn btn-success" {{ $docsMissing ? 'disabled title="Falta alvará"' : '' }}>
                {!! $docsMissing ? '🔒 Aprovar (Falta Alvará)' : 'Aprovar Candidatura' !!}
            </button>
        </form>
    </div>
    @endif
</div>

<div class="card" style="max-width: 800px;">
    <h2 style="font-size: 1.5rem; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
        {{ $application->name }}
        @if($application->status === 'pending')
            <span class="badge badge-warning">Pendente</span>
        @elseif($application->status === 'approved')
            <span class="badge badge-success">Aprovado</span>
        @else
            <span class="badge badge-danger">Rejeitado</span>
        @endif
    </h2>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
        <div>
            <div class="text-muted text-sm fw-600 uppercase" style="margin-bottom: 4px; font-size: 11px; letter-spacing: 1px;">Contacto</div>
            <div style="margin-bottom: 4px;"><strong>Telefone:</strong> {{ $application->phone }}</div>
            <div><strong>Email:</strong> {{ $application->email ?? '—' }}</div>
        </div>
        <div>
            <div class="text-muted text-sm fw-600 uppercase" style="margin-bottom: 4px; font-size: 11px; letter-spacing: 1px;">Informações Empresariais</div>
            <div style="margin-bottom: 4px;"><strong>NIF:</strong> {{ $application->nif ?? '—' }}</div>
            <div><strong>Data Registo:</strong> {{ $application->created_at->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    @if($application->address)
    <div style="margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--border);">
        <div class="text-muted text-sm fw-600 uppercase" style="margin-bottom: 8px; font-size: 11px; letter-spacing: 1px;">Morada</div>
        <p style="margin: 0; line-height: 1.5;">{{ $application->address }}</p>
    </div>
    @endif

    <div style="{{ !$application->address ? 'border-top: 1px solid var(--border); padding-top: 24px;' : '' }}">
        <h3 style="font-size: 1.1rem; margin-bottom: 16px;">Documentos Anexos</h3>
        <div style="display: flex; gap: 24px; flex-wrap: wrap;">
            {{-- Alvará --}}
            <div style="flex: 1; min-width: 300px; background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px dashed var(--border);">
                <div class="text-muted text-sm fw-600 uppercase" style="margin-bottom: 12px; font-size: 11px; letter-spacing: 1px;">Alvará / Licença</div>
                @if($application->business_license_path)
                    <div style="margin-bottom: 12px;">
                        <a href="{{ asset('storage/' . $application->business_license_path) }}" target="_blank" class="btn btn-outline" style="width: 100%; justify-content: center;">
                            📄 Ver Alvará Atual
                        </a>
                    </div>
                @else
                    <div style="color: #b91c1c; font-size: 0.9rem; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Documento em falta
                    </div>
                @endif
                
                @if($application->status === 'pending')
                <form method="POST" action="{{ route('admin.restaurant_applications.uploadDocument', $application) }}" enctype="multipart/form-data" style="margin:0; display: flex; flex-direction: column; gap: 8px;">
                    @csrf
                    <input type="hidden" name="document_type" value="business_license">
                    <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required style="font-size: 0.85rem; padding: 6px; border: 1px solid var(--border); border-radius: 6px; background: #fff;">
                    <button type="submit" class="btn btn-sm btn-primary">Carregar Alvará</button>
                </form>
                @endif
            </div>
        </div>
    </div>

    @if($application->status === 'rejected' && $application->rejection_reason)
    <div style="margin-top: 24px; padding: 16px; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px;">
        <h4 style="color: #991b1b; margin-bottom: 8px;">Motivo da Rejeição:</h4>
        <p style="color: #7f1d1d; margin: 0;">{{ $application->rejection_reason }}</p>
    </div>
    @endif
</div>

@if($application->status === 'pending')
<script>
async function promptReject(btn) {
    const { value: text } = await Swal.fire({
        title: 'Motivo da Rejeição',
        input: 'textarea',
        inputLabel: 'Introduza o motivo da rejeição (obrigatório)',
        inputPlaceholder: 'Ex: NIF inválido...',
        showCancelButton: true,
        confirmButtonText: 'Confirmar Rejeição',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#EB2835',
        inputValidator: (value) => {
            if (!value) {
                return 'Tem de indicar um motivo!'
            }
        }
    });

    if (text) {
        let form = btn.closest('form');
        form.querySelector('.rejection-reason-input').value = text;
        form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
    }
}
</script>
@endif
@endsection
