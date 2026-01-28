@extends('app.template')

@section('title', 'Gestión de Usuarios')

@section('modal')
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteUserModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-0">¿Estás seguro de que deseas eliminar al usuario <strong id="userNameToDelete"></strong>?</p>
                <p class="text-muted small mt-2">Esta acción no se puede deshacer y el usuario perderá acceso inmediato al sistema.</p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteUserForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-person-x-fill me-1"></i> Eliminar Usuario
                    </button>
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
                <h2 class="fw-bold"><i class="bi bi-people-fill text-primary"></i> Control de Usuarios</h2>
                <a href="{{ route('user.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Usuario</th>
                                    <th>Email</th>
                                    <th>Rol actual</th>
                                    <th>Fecha Registro</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-circle fs-3 me-2 text-secondary"></i>
                                            <span class="fw-bold">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->rol == 'admin')
                                            <span class="badge bg-danger">Administrador</span>
                                        @elseif($user->rol == 'advanced')
                                            <span class="badge bg-warning text-dark">Avanzado</span>
                                        @else
                                            <span class="badge bg-info text-dark">Usuario</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-outline-warning" title="Cambiar Rol">
                                                <i class="bi bi-shield-lock"></i>
                                            </a>
                                            
                                            @if($user->id !== Auth::id())
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger btn-delete-user" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteUserModal"
                                                        data-id="{{ $user->id }}"
                                                        data-name="{{ $user->name }}"
                                                        data-url="{{ route('user.destroy', $user->id) }}">
                                                    <i class="bi bi-person-x"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <!-- Paginación -->
                {{ $users->links() }} 
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Seleccionamos todos los botones que abren el modal de borrado
        const deleteButtons = document.querySelectorAll('.btn-delete-user');
        const deleteForm = document.getElementById('deleteUserForm');
        const nameSpan = document.getElementById('userNameToDelete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Extraemos la info de los atributos data-
                const userId = this.getAttribute('data-id');
                const userName = this.getAttribute('data-name');
                const deleteUrl = this.getAttribute('data-url');

                // Inyectamos el nombre en el texto del modal
                nameSpan.textContent = userName;

                // Cambiamos el action del formulario para que apunte a la ruta correcta
                deleteForm.setAttribute('action', deleteUrl);
            });
        });
    });
</script>
@endsection