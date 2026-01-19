@extends('layouts.app')

@section('title', 'Moderación de Comentarios')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-chat-left-quote text-primary"></i> Gestión de Comentarios</h2>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Usuario</th>
                            <th>Destino Vacacional</th>
                            <th>Comentario</th>
                            <th>Fecha</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comentarios as $comentario)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $comentario->usuario->name }}</div>
                                <small class="text-muted">{{ $comentario->usuario->email }}</small>
                            </td>
                            <td>
                                <a href="{{ route('vacacion.show', $comentario->idvacacion) }}" class="text-decoration-none">
                                    {{ $comentario->vacacion->titulo }}
                                </a>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    {{ Str::limit($comentario->comentario, 60) }}
                                </span>
                            </td>
                            <td class="small">{{ $comentario->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('comentario.destroy', $comentario->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este comentario permanentemente?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No hay comentarios registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $comentarios->links() }}
    </div>
</div>
@endsection