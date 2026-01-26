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
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">
                    <i class="bi bi-calendar-check text-primary"></i> 
                    {{ Auth::user()->rol == 'admin' ? 'Gestión Global de Reservas' : 'Mis Viajes Reservados' }}
                </h2>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Ref.</th>
                                    @if(Auth::user()->rol == 'admin')
                                        <th>Cliente</th>
                                    @endif
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
                                    
                                    @if(Auth::user()->rol == 'admin')
                                        <td>
                                            <div class="fw-bold">{{ $reserva->user->name }}</div>
                                            <div class="small text-muted">{{ $reserva->user->email }}</div>
                                        </td>
                                    @endif

                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($reserva->vacacion->foto->count() > 0)
                                                <img src="{{ asset('storage/' . $reserva->vacacion->foto->first()->ruta) }}" 
                                                     class="rounded me-2" style="width: 50px; height: 40px; object-fit: cover;">
                                            @endif
                                            <span class="fw-bold">{{ $reserva->vacacion->titulo }}</span>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}
                                        </span>
                                    </td>

                                    <td class="fw-bold text-primary">
                                        {{ number_format($reserva->vacacion->precio, 2) }}€
                                    </td>

                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="{{ route('vacacion.show', $reserva->idvacacion) }}" class="btn btn-sm btn-outline-primary" title="Ver destino">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteReservaModal"
                                                    data-bs-id="{{ $reserva->id }}"
                                                    data-bs-destino="{{ $reserva->vacacion->titulo }}"
                                                    data-bs-fecha="{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}">
                                                <i class="bi bi-x-circle me-1"></i> Cancelar Reserva
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->rol == 'admin' ? '6' : '5' }}" class="text-center py-5">
                                        <i class="bi bi-luggage fs-1 text-muted d-block mb-3"></i>
                                        <p class="text-muted">No hay ninguna reserva registrada.</p>
                                        <a href="{{ route('vacacion.index') }}" class="btn btn-primary btn-sm">Explorar destinos</a>
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
            
            // LA CORRECCIÓN CLAVE:
            // Construimos la URL usando la ruta base de la página actual para evitar el 404
            const form = deleteReservaModal.querySelector('#deleteReservaForm');
            const currentUrl = window.location.href.split('?')[0]; // Quitamos parámetros GET
            
            // Si la URL actual es .../reserva, solo añadimos el ID
            form.action = `${currentUrl}/${id}`;
        });
    }
</script>
@endsection