@extends('app.template')

@section('title', 'Gestión de Categorías')

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
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Nombre de la Categoría</th>
                                <th>Nº de Paquetes</th> {{-- Opcional: para saber si se usa --}}
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
                                        <a href="{{ route('tipo.edit', $tipo->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        {{-- El botón de borrar solo debería funcionar si no hay vacaciones asociadas (por integridad) --}}
                                        <form action="{{ route('tipo.destroy', $tipo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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
                <a href="{{ route('vacacion.index') }}" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left"></i> Volver a vacaciones
                </a>
            </div>
        </div>
    </div>
</div>
@endsection