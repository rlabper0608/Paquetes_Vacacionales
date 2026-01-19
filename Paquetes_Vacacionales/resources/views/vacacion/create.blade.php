@extends('app.template')

@section('title', 'Crear Nuevo Paquete Vacacional')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Publicar Nuevo Paquete</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('vacacion.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        {{-- Título --}}
                        <div class="col-md-12 mb-3">
                            <label for="titulo" class="form-label fw-bold">Título del Viaje</label>
                            <input type="text" name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo') }}" placeholder="Ej: Crucero por las Islas Griegas">
                            @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Descripción --}}
                        <div class="col-md-12 mb-3">
                            <label for="descripcion" class="form-label fw-bold">Descripción Detallada</label>
                            <textarea name="descripcion" id="descripcion" rows="4" class="form-control @error('descripcion') is-invalid @enderror" placeholder="Describe el itinerario, qué incluye...">{{ old('descripcion') }}</textarea>
                            @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Precio --}}
                        <div class="col-md-6 mb-3">
                            <label for="precio" class="form-label fw-bold">Precio (€)</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" step="0.01" name="precio" id="precio" class="form-control @error('precio') is-invalid @enderror" value="{{ old('precio') }}">
                            </div>
                            @error('precio') <div class="invalid-feedback text-danger small">{{ $message }}</div> @enderror
                        </div>

                        {{-- Tipo (Categoría) --}}
                        <div class="col-md-6 mb-3">
                            <label for="idtipo" class="form-label fw-bold">Categoría de Viaje</label>
                            <select name="idtipo" id="idtipo" class="form-select @error('idtipo') is-invalid @enderror">
                                <option value="" selected disabled>Selecciona un tipo...</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id }}" {{ old('idtipo') == $tipo->id ? 'selected' : '' }}>
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idtipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Subida de Fotos --}}
                        <div class="col-md-12 mb-4">
                            <label for="fotos" class="form-label fw-bold">Fotos del Destino (Puedes subir varias)</label>
                            <input type="file" name="fotos[]" id="fotos" class="form-control @error('fotos.*') is-invalid @enderror" multiple accept="image/*">
                            <div class="form-text text-muted">Selecciona una o más imágenes para la galería.</div>
                            @error('fotos.*') <div class="invalid-feedback">Una de las fotos no es válida.</div> @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('vacacion.index') }}" class="btn btn-light border">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-2"></i>Guardar Paquete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection