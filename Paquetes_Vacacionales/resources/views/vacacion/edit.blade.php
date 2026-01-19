@extends('app.template')

@section('title', 'Editar Paquete: ' . $vacacion->titulo)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-0">
            <div class="card-header bg-warning text-dark py-3">
                <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Editar: {{ $vacacion->titulo }}</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('vacacion.update', $vacacion->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Título --}}
                        <div class="col-md-12 mb-3">
                            <label for="titulo" class="form-label fw-bold">Título del Viaje</label>
                            <input type="text" name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror" 
                                   value="{{ old('titulo', $vacacion->titulo) }}">
                            @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Descripción --}}
                        <div class="col-md-12 mb-3">
                            <label for="descripcion" class="form-label fw-bold">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="4" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $vacacion->descripcion) }}</textarea>
                            @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Precio y Tipo --}}
                        <div class="col-md-6 mb-3">
                            <label for="precio" class="form-label fw-bold">Precio (€)</label>
                            <input type="number" step="0.01" name="precio" id="precio" class="form-control @error('precio') is-invalid @enderror" 
                                   value="{{ old('precio', $vacacion->precio) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="idtipo" class="form-label fw-bold">Categoría</label>
                            <select name="idtipo" id="idtipo" class="form-select">
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id }}" {{ (old('idtipo', $vacacion->idtipo) == $tipo->id) ? 'selected' : '' }}>
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Gestión de Fotos Actuales con opción de eliminar --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold text-danger">Fotos actuales (Marca para eliminar)</label>
                            <div class="d-flex flex-wrap gap-3 mb-3 p-3 bg-light rounded border">
                                @forelse($vacacion->foto as $fot)
                                    <div class="text-center border bg-white p-2 rounded shadow-sm">
                                        <img src="{{ asset('storage/' . $fot->ruta) }}" 
                                             class="rounded mb-2" 
                                             style="width: 100px; height: 100px; object-fit: cover;">
                                        <div class="form-check justify-content-center d-flex">
                                            <input class="form-check-input me-1" type="checkbox" name="eliminar_fotos[]" value="{{ $fot->id }}" id="foto_{{ $fot->id }}">
                                            <label class="form-check-label small text-danger fw-bold" for="foto_{{ $fot->id }}">
                                                Borrar
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted small mb-0">No hay fotos en este paquete.</p>
                                @endforelse
                            </div>
                            
                            <label for="fotos" class="form-label fw-bold">Añadir más fotos</label>
                            <input type="file" name="fotos[]" id="fotos" class="form-control" multiple accept="image/*">
                            <div class="form-text">Puedes seleccionar varias imágenes a la vez.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('vacacion.index') }}" class="btn btn-light border">Cancelar</a>
                        <button type="submit" class="btn btn-warning px-4 fw-bold">
                            Actualizar Paquete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection