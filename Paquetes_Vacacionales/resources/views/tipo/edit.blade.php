@extends('app.template')

@section('title', 'Editar Categoría: ' . $tipo->nombre)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Modificar Categoría</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('tipo.update', $tipo->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="nombre" class="form-label fw-bold">Nombre de la Categoría</label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               class="form-control @error('nombre') is-invalid @enderror" 
                               value="{{ old('nombre', $tipo->nombre) }}" 
                               required 
                               autofocus>
                        
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <div class="form-text mt-2 text-warning">
                            <i class="bi bi-info-circle"></i> Al cambiar este nombre, se actualizará automáticamente en todos los paquetes vacacionales asociados.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('tipo.index') }}" class="text-decoration-none text-muted">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning px-4 fw-bold">
                            <i class="bi bi-arrow-repeat me-1"></i> Actualizar Categoría
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection