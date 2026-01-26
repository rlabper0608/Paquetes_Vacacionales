@extends('app.template')

@section('modal')
{{-- MODAL ÚNICO DE ELIMINACIÓN --}}
<div class="modal fade" id="deleteVacacionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-octagon-fill text-warning me-2"></i> Eliminar Paquete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="fs-5">¿Deseas eliminar permanentemente este destino?</p>
                {{-- CAMBIO AQUÍ: Usamos un ID para que el JS rellene el título --}}
                <h4 class="fw-bold text-danger" id="vacacion-titulo-preview"></h4>
                <div class="alert alert-warning mt-3 mb-0 small">
                    <i class="bi bi-info-circle me-1"></i> Se borrarán todas las fotos y datos asociados.
                </div>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteVacacionForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Confirmar Eliminación</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Gestión de Paquetes Vacacionales</h2>
        <a href="{{ route('vacacion.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Paquete
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Tipo</th>
                        <th>Precio</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vacaciones as $v)
                    <tr>
                        <td><span class="text-muted small">#{{ $v->id }}</span></td>
                        <td><strong>{{ $v->titulo }}</strong></td>
                        <td><span class="badge bg-info text-dark">{{ $v->tipo->nombre }}</span></td>
                        <td class="fw-bold text-primary">{{ number_format($v->precio, 2) }}€</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('vacacion.show', $v->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('vacacion.edit', $v->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                
                                {{-- BOTÓN CORREGIDO: data-bs-target debe ser #deleteVacacionModal --}}
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteVacacionModal" 
                                        data-bs-id="{{ $v->id }}" 
                                        data-bs-titulo="{{ $v->titulo }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteVacacionModal = document.getElementById('deleteVacacionModal');
        if (deleteVacacionModal) {
            deleteVacacionModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-bs-id');
                const titulo = button.getAttribute('data-bs-titulo'); // Extraemos el título
                
                const form = deleteVacacionModal.querySelector('#deleteVacacionForm');
                const tituloPreview = deleteVacacionModal.querySelector('#vacacion-titulo-preview');
                
                // Rellenamos el título y la ruta del form
                if (tituloPreview) tituloPreview.textContent = titulo;
                if (form) {
                    form.action = "{{ url('vacacion') }}/" + id;
                }
            });
        }
    });
</script>
@endsection