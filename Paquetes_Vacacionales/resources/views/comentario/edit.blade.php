@extends('app.template')
@extends('app.template')

@section('title', 'Editar Comentario')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Tarjeta de Edición --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Editar tu opinión
                    </h5>
                </div>
                <div class="card-body p-4">
                    {{-- Información del Paquete (Contexto) --}}
                    <div class="mb-4 p-3 bg-light rounded d-flex align-items-center">
                        <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                        <div>
                            <span class="text-muted d-block small">Estas editando tu comentario sobre:</span>
                            <strong class="text-dark">{{ $comentario->vacacion->titulo }}</strong>
                        </div>
                    </div>

                    {{-- Formulario de Edición --}}
                    <form action="{{ route('comentario.update', $comentario) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="comentario" class="form-label fw-bold">Tu comentario</label>
                            <textarea 
                                name="comentario" 
                                id="comentario" 
                                rows="5" 
                                class="form-control @error('comentario') is-invalid @enderror" 
                                placeholder="Escribe aquí tu nueva opinión..."
                                required>{{ old('comentario', $comentario->comentario) }}</textarea>
                            
                            @error('comentario')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            {{-- Botón Volver --}}
                            <a href="{{ route('vacacion.show', $comentario->idvacacion) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Cancelar y volver
                            </a>

                            {{-- Botón Guardar --}}
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="bi bi-save me-2"></i>Actualizar Comentario
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Nota de seguridad --}}
            <div class="mt-3 text-center">
                <small class="text-muted">
                    <i class="bi bi-shield-lock me-1"></i> Solo tú puedes editar este comentario.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection