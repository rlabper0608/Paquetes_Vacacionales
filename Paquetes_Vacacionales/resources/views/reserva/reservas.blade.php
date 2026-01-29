@extends('app.template')

@section('modal')
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i> Confirmar Cancelación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="fs-5">¿Estás seguro de que deseas cancelar la reserva de <strong id="modalCustomerName"></strong>?</p>
                <p class="text-muted small">Esta acción no se puede deshacer y el paquete volverá a estar disponible.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, mantener</button>
                {{-- Importante: El action se llena mediante JS --}}
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Sí, cancelar reserva</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-calendar-check text-primary"></i> Gestión Global de Reservas</h1>
        <span class="badge bg-dark">
            {{ method_exists($reservas, 'total') ? $reservas->total() : $reservas->count() }} Reservas totales
        </span>
    </div>

    @if(session('mensajeTexto'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('mensajeTexto') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Cliente</th>
                            <th>Email</th>
                            <th>Paquete Vacacional</th>
                            <th>Fecha de Reserva</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservas as $reserva)
                            <tr>
                                <td class="ps-4 fw-bold">#{{ $reserva->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-circle me-2 fs-5 text-secondary"></i>
                                        <div>
                                            <span class="d-block fw-bold">{{ $reserva->user->name ?? 'Usuario borrado' }}</span>
                                            <small class="badge bg-info text-dark" style="font-size: 0.7rem;">
                                                {{ strtoupper($reserva->user->rol ?? 'N/A') }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $reserva->user->email ?? 'S/E' }}</td>
                                <td>
                                    @if($reserva->vacacion)
                                        <a href="{{ route('vacacion.show', $reserva->vacacion->id) }}" class="text-decoration-none fw-bold">
                                            {{ $reserva->vacacion->nombre }}
                                        </a>
                                    @else
                                        <span class="text-muted italic">Paquete no disponible</span>
                                    @endif
                                </td>
                                <td>
                                    <i class="bi bi-clock me-1 text-muted"></i>
                                    {{ $reserva->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="text-center">
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal" 
                                            data-id="{{ $reserva->id }}" 
                                            {{-- Aseguramos que data-name no vaya vacío para el JS --}}
                                            data-name="{{ $reserva->user->name ?? 'esta reserva' }}">
                                        <i class="bi bi-trash"></i> Cancelar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-emoji-frown fs-1 d-block mb-2"></i>
                                    No se han realizado reservas todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($reservas, 'links'))
            <div class="card-footer bg-white d-flex justify-content-center">
                {{ $reservas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteModal');
        
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                // Botón que disparó el modal
                const button = event.relatedTarget;
                
                // Extraer información
                const reservaId = button.getAttribute('data-id');
                const customerName = button.getAttribute('data-name');
                
                // Elementos del modal
                const modalCustomerName = deleteModal.querySelector('#modalCustomerName');
                const deleteForm = deleteModal.querySelector('#deleteForm');
                
                // Asignar nombre al texto del modal
                modalCustomerName.textContent = customerName;
                
                /**
                 * CORRECCIÓN DE URL:
                 * Usamos una ruta relativa al directorio actual para evitar problemas de subcarpetas.
                 * Si tu ruta en web.php es Route::resource('reserva', ...), 
                 * la URL debe ser "reserva/ID"
                 */
                const currentPath = window.location.pathname.split('/public/')[0];
                deleteForm.action = `${currentPath}/public/reserva/${reservaId}`;
                
                // Log para depuración (puedes verlo en la consola F12 si falla)
                console.log("Intentando borrar en: " + deleteForm.action);
            });
        }
    });
</script>
@endsection