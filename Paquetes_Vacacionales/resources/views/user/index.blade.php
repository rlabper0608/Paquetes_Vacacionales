@extends('app.template')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold"><i class="bi bi-people-fill text-primary"></i> Control de Usuarios</h2>
                {{-- Opcional: Botón para crear usuario manualmente --}}
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
                                            
                                            {{-- Evitar que el admin se borre a sí mismo --}}
                                            @if($user->id !== Auth::id())
                                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar a este usuario?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-person-x"></i>
                                                </button>
                                            </form>
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
                {{ $users->links() }} {{-- Paginación --}}
            </div>
        </div>
    </div>
</div>
@endsection