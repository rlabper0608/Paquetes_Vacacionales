@extends('app.template')

@section('title', 'Gestión de Categorías')

@section('modal')
<div class="modal fade" id="deleteTipoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i> Eliminar Categoría</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="fs-5">¿Estás seguro de que deseas eliminar esta categoría?</p>
                <h4 class="fw-bold text-danger" id="tipo-nombre-preview"></h4>
                <div class="alert alert-warning mt-3 mb-0 small">
                    <i class="bi bi-info-circle me-1"></i> <strong>Nota:</strong> Solo podrás eliminarla si no tiene paquetes vacacionales asociados.
                </div>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteTipoForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">Confirmar Eliminación</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold"><i class="bi bi-tags text-primary"></i> Categorías de Viajes</h2>
                <a href="{{ route('tipo.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Nombre de la Categoría</th>
                                <th>Nº de Paquetes</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tipos as $tipo)
                            <tr>
                                <td class="ps-4 text-muted">#{{ $tipo->id }}</td>
                                <td><span class="fw-bold">{{ $tipo->nombre }}</span></td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $tipo->vacacion->count() }} viajes
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('tipo.edit', $tipo->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteTipoModal"
                                                data-bs-id="{{ $tipo->id }}"
                                                data-bs-nombre="{{ $tipo->nombre }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    No hay categorías creadas todavía.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-3">
                <a href="{{ route('vacacion.index') }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left"></i> Volver a gestión de vacaciones
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteTipoModal = document.getElementById('deleteTipoModal');
        if (deleteTipoModal) {
            deleteTipoModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; 
                const id = button.getAttribute('data-bs-id');
                const nombre = button.getAttribute('data-bs-nombre');
                
                // Actualizar el texto del modal
                const nombrePreview = deleteTipoModal.querySelector('#tipo-nombre-preview');
                nombrePreview.textContent = nombre;
                
                // Actualizar la ruta del formulario
                const form = deleteTipoModal.querySelector('#deleteTipoForm');
                form.action = "{{ url('tipo') }}/" + id;
            });
        }
    });
</script>
@endsection