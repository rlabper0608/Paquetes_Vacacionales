@extends('app.template')

@section('title', 'Nuestros Paquetes Vacacionales')

@section('content')
<div class="container">
    {{-- Cabecera con botón de añadir para Admins --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-geo-alt-fill text-primary"></i> Destinos Disponibles</h2>
        
        @auth
            @if(Auth::user()->rol == 'admin' || Auth::user()->rol == 'advanced')
                <a href="{{ route('vacacion.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Nuevo Paquete
                </a>
            @endif
        @endauth
    </div>

    {{-- Rejilla de Vacaciones --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($vacaciones as $vacacion)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">

                    <div class="position-relative">
                        @if($vacacion->foto->count() > 0)
                            <img src="{{ asset('storage/' . $vacacion->foto->first()->ruta) }}" 
                                 class="card-img-top" alt="{{ $vacacion->titulo }}" 
                                 style="height: 220px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 220px;">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <span class="position-absolute top-0 end-0 m-2 badge bg-dark opacity-75">
                            {{ $vacacion->tipo->nombre }}
                        </span>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $vacacion->titulo }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($vacacion->descripcion, 80) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0 text-primary fw-bold">{{ number_format($vacacion->precio, 2) }}€</span>
                            <a href="{{ route('vacacion.show', $vacacion->id) }}" class="btn btn-outline-primary btn-sm">Ver más</a>
                        </div>
                    </div>

                    @auth
                        @if(Auth::user()->rol == 'admin')
                            <div class="card-footer bg-transparent d-flex justify-content-center gap-2">
                                <a href="{{ route('vacacion.edit', $vacacion->id) }}" class="btn btn-sm btn-light text-warning">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form action="{{ route('vacacion.destroy', $vacacion->id) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres borrar este paquete?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger">
                                        <i class="bi bi-trash"></i> Borrar
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    No hay paquetes vacacionales disponibles en este momento.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Paginación (si usas ->paginate() en el controlador) --}}
    <div class="d-flex justify-content-center mt-5">
        {{ $vacaciones->links() }}
    </div>
</div>
@endsection