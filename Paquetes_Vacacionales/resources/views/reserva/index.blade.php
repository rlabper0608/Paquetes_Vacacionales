@extends('app.template')

@section('title', 'Mis Reservas')

@section('modal')
<div class="modal fade" id="deleteReservaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i> ¿Cancelar esta reserva?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="fs-5">Estás a punto de cancelar tu viaje a:</p>
                <h4 class="text-primary fw-bold" id="reserva-destino-preview"></h4>
                <p class="text-muted">Programado para el: <span id="reserva-fecha-preview" class="fw-bold"></span></p>
                <div class="alert alert-light border mt-3 small text-muted">
                    <i class="bi bi-info-circle me-1"></i> Esta acción liberará tu plaza y no se podrá deshacer.
                </div>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">No, mantener reserva</button>
                <form id="deleteReservaForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">Sí, cancelar viaje</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">
                    <i class="bi bi-briefcase text-primary me-2"></i> Mis Viajes Reservados
                </h2>
                <span class="badge bg-primary rounded-pill">{{ $reservas->count() }} viajes</span>
            </div>

            @if(session('mensajeTexto'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('mensajeTexto') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Ref.</th>
                                    <th>Destino</th>
                                    <th>Fecha del Viaje</th>
                                    <th>Precio</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservas as $reserva)
                                <tr>
                                    <td class="ps-4 text-muted small">#RSV-{{ $reserva->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($reserva->vacacion->foto && $reserva->vacacion->foto->count() > 0)
                                                <img src="{{ asset('storage/' . $reserva->vacacion->foto->first()->ruta) }}" 
                                                     class="rounded me-3 shadow-sm" style="width: 60px; height: 45px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 45px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="d-block fw-bold">{{ $reserva->vacacion->titulo }}</span>
                                                <small class="text-muted">{{ $reserva->vacacion->tipo->nombre ?? 'Vacación' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <span class="badge bg-white text-dark border px-3 py-2">
                                            <i class="bi bi-calendar3 me-2 text-primary"></i>
                                            {{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}
                                        </span>
                                    </td>

                                    <td class="fw-bold text-dark">
                                        {{ number_format($reserva->vacacion->precio, 2) }}€
                                    </td>

                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-sm">
                                            <a href="{{ route('vacacion.show', $reserva->idvacacion) }}" class="btn btn-sm btn-white border" title="Ver detalles">
                                                <i class="bi bi-eye text-primary"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-white border text-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteReservaModal"
                                                    data-bs-id="{{ $reserva->id }}"
                                                    data-bs-destino="{{ $reserva->vacacion->titulo }}"
                                                    data-bs-fecha="{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}">
                                                <i class="bi bi-trash3 me-1"></i> Cancelar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-luggage-fill fs-1 text-light-emphasis d-block mb-3"></i>
                                            <h4 class="text-muted">Aún no tienes viajes reservados</h4>
                                            <p class="text-muted mb-4">¡Explora nuestros destinos y empieza tu aventura!</p>
                                            <a href="{{ route('vacacion.index') }}" class="btn btn-primary px-4 rounded-pill">
                                                <i class="bi bi-search me-2"></i>Buscar destinos
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const deleteReservaModal = document.getElementById('deleteReservaModal');
    if (deleteReservaModal) {
        deleteReservaModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-bs-id');
            const destino = button.getAttribute('data-bs-destino');
            const fecha = button.getAttribute('data-bs-fecha');
            
            deleteReservaModal.querySelector('#reserva-destino-preview').textContent = destino;
            deleteReservaModal.querySelector('#reserva-fecha-preview').textContent = fecha;

            const form = deleteReservaModal.querySelector('#deleteReservaForm');
            const currentUrl = window.location.href.split('?')[0]; 
            
            form.action = `${currentUrl}/${id}`;
        });
    }
</script>
@endsection