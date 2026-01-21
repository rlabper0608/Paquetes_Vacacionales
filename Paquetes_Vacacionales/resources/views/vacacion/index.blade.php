@extends('app.template')

@section('title', 'Nuestros Paquetes Vacacionales')

{{-- SECCIÓN DE MODALES --}}
@section('content_modals')
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="orderModalLabel">Ordenar paquetes por...</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a class="btn btn-link text-decoration-none"
                            href="{{ route('vacacion.index', array_merge(['field' => 1, 'order' => 2], request()->except('field', 'order', 'page'))) }}">
                            Los más recientes primero</a>
                    </li>
                    <li class="list-group-item">
                        <a class="btn btn-link text-decoration-none"
                            href="{{ route('vacacion.index', array_merge(['field' => 1, 'order' => 1], request()->except('field', 'order', 'page'))) }}">
                            Los más antiguos primero</a>
                    </li>
                    <li class="list-group-item">
                        <a class="btn btn-link text-decoration-none" 
                            href="{{ route('vacacion.index', array_merge(['field' => 2, 'order' => 1], request()->except('field', 'order', 'page'))) }}">
                            Precio: más barato primero</a>
                    </li>
                    <li class="list-group-item">
                        <a class="btn btn-link text-decoration-none" 
                            href="{{ route('vacacion.index', array_merge(['field' => 2, 'order' => 2], request()->except('field', 'order', 'page'))) }}">
                            Precio: más caro primero</a>
                    </li>
                    <li class="list-group-item">
                        <a class="btn btn-link text-decoration-none"
                            href="{{ route('vacacion.index', array_merge(['field' => 3, 'order' => 1], request()->except('field', 'order', 'page'))) }}">
                            Categoría (A-Z)</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="filterModalLabel">Filtrar paquetes por...</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm" action="{{ route('vacacion.index') }}" method="get">
                    <input type="hidden" name="field" value="{{ request('field') }}">
                    <input type="hidden" name="order" value="{{ request('order') }}">

                    <div class="mb-3">
                        <label for="idtipo" class="form-label">Categoría</label>
                        <select name="idtipo" id="idtipo" class="form-control">
                            <option value="" {{ request('idtipo') == null ? 'selected' : '' }}>Selecciona una categoría...</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}" {{ request('idtipo') == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rango de precio</label>
                        <div class="input-group mb-2">
                            <input type="number" class="form-control" name="desde" value="{{ request('desde') }}" placeholder="Mínimo">
                            <span class="input-group-text">€</span>
                        </div>
                        <div class="input-group">
                            <input type="number" class="form-control" name="hasta" value="{{ request('hasta') }}" placeholder="Máximo">
                            <span class="input-group-text">€</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Aplicar Filtros</button>
                    <a href="{{ route('vacacion.index') }}" class="btn btn-outline-secondary w-100 mt-2">Limpiar todo</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- CONTENIDO PRINCIPAL --}}
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-4">
        <h2 class="fw-bold">Nuestros Destinos</h2>
        <div>
            <button class="btn btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#orderModal">
                <i class="bi bi-sort-down"></i> Ordenar
            </button>
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="bi bi-filter"></i> Filtrar
            </button>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($vacaciones as $vacacion)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    {{-- Imagen del Paquete --}}
                    <div class="position-relative">
                        @if($vacacion->foto->count() > 0)
                            <img src="{{ asset('storage/' . $vacacion->foto->first()->ruta) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-image text-muted fs-1"></i>
                            </div>
                        @endif
                        <span class="position-absolute top-0 end-0 m-2 badge bg-primary">
                            {{ $vacacion->tipo->nombre }}
                        </span>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title fw-bold">
                            <a href="{{ route('vacacion.show', $vacacion->id) }}" class="text-decoration-none text-dark">
                                {{ $vacacion->titulo }}
                            </a>
                        </h5>
                        <p class="card-text text-muted small">{{ Str::limit($vacacion->descripcion, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold text-primary fs-5">{{ number_format($vacacion->precio, 2) }}€</span>
                            <a href="{{ route('vacacion.show', $vacacion->id) }}" class="btn btn-sm btn-primary">Detalles</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Paginación --}}
    <div class="d-flex justify-content-center mt-5">
        {{ $vacaciones->onEachSide(2)->links() }}
    </div>
</div>

{{-- No olvides añadir esto en tu app.template si no lo tienes para renderizar los modales --}}
@yield('content_modals')

@endsection