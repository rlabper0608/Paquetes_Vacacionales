@extends('app.template')

@section('title', 'Gestionar Galería - ' . $vacacion->titulo)

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Galería de Fotos</h2>
            <p class="text-muted">Paquete: <strong>{{ $vacacion->titulo }}</strong></p>
        </div>
        <a href="{{ route('vacacion.edit', $vacacion->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver a edición
        </a>
    </div>

    {{-- Subida rápida de fotos --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('foto.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf
                <input type="hidden" name="idvacacion" value="{{ $vacacion->id }}">
                <div class="col-md-9">
                    <input type="file" name="fotos[]" class="form-control" multiple required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-upload me-2"></i>Subir Fotos
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Cuadrícula de fotos actuales --}}
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        @forelse($vacacion->foto as $fot)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('storage/' . $fot->ruta) }}" class="card-img-top rounded" 
                         style="height: 180px; object-fit: cover;" alt="Foto vacación">
                    <div class="card-body text-center p-2">
                        <form action="{{ route('foto.destroy', $fot->id) }}" method="POST" 
                              onsubmit="return confirm('¿Eliminar esta imagen definitivamente?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash me-1"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Este paquete aún no tiene imágenes en su galería.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection