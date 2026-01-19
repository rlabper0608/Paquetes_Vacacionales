@extends('app.template')

@section('title', 'Nueva Categoría')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-tag-fill me-2"></i>Nueva Categoría de Viaje</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('tipo.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="nombre" class="form-label fw-bold">Nombre de la Categoría</label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               class="form-control @error('nombre') is-invalid @enderror" 
                               value="{{ old('nombre') }}" 
                               placeholder="Ej: Montaña, Playa, Aventura..." 
                               required 
                               autofocus>
                        
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <div class="form-text mt-2">
                            Asegúrate de que el nombre sea descriptivo para que los usuarios encuentren mejor sus vacaciones.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('tipo.index') }}" class="text-decoration-none text-muted">
                            <i class="bi bi-arrow-left"></i> Volver al listado
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Guardar Categoría
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection