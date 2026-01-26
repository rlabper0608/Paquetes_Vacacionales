@extends('app.template')

@section('title', $vacacion->titulo)

@section('modal')
<div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle me-2"></i>¿Confirmar borrado?</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Estás a punto de eliminar el comentario:</p>
                <blockquote class="blockquote fs-6 text-muted p-2 bg-light rounded">
                    <span id="comment-text-preview"></span>
                </blockquote>
                <p class="mb-0 text-danger small fw-bold">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteCommentForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar permanentemente</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row g-4">
        {{-- Columna Izquierda: Galería y Descripción --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                {{-- Carrusel de Fotos --}}
                @if($vacacion->foto->count() > 0)
                    <div id="carouselVacacion" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($vacacion->foto as $key => $fot)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $fot->ruta) }}" class="d-block w-100 rounded-top" style="height: 450px; object-fit: cover;" alt="Foto de {{ $vacacion->titulo }}">
                                </div>
                            @endforeach
                        </div>
                        @if($vacacion->foto->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselVacacion" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselVacacion" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded-top" style="height: 400px;">
                        <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                    </div>
                @endif

                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-primary px-3 py-2">{{ $vacacion->tipo->nombre }}</span>
                        <h4 class="text-primary fw-bold mb-0">{{ number_format($vacacion->precio, 2) }}€</h4>
                    </div>
                    <h1 class="fw-bold">{{ $vacacion->titulo }}</h1>
                    <hr>
                    <p class="lead text-muted" style="white-space: pre-line;">{{ $vacacion->descripcion }}</p>
                </div>
            </div>

            {{-- Sección de Comentarios --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4"><i class="bi bi-chat-dots me-2"></i>Comentarios</h4>
                    
                    @forelse($vacacion->comentario as $comentari)
                        <div class="d-flex mb-3 border-bottom pb-3">
                            <div class="flex-shrink-0">
                                <i class="bi bi-person-circle fs-2 text-secondary"></i>
                            </div>
                            <div class="ms-3 w-100">
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-bold mb-0">{{ $comentari->user->name }}</h6>
                                    
                                    <div class="d-flex gap-2">
                                        @auth
                                            @if(Auth::id() == $comentari->iduser)
                                                <a href="{{ route('comentario.edit', $comentari) }}" class="btn btn-sm btn-outline-primary py-0 px-2" title="Editar">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endif
                                            
                                            @if(Auth::id() == $comentari->iduser || Auth::user()->rol == 'admin')
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger py-0 px-2" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteCommentModal"
                                                        data-bs-id="{{ $comentari->id }}"
                                                        data-bs-texto="{{ Str::limit($comentari->comentario, 30) }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                                <small class="text-muted">{{ $comentari->created_at->diffForHumans() }}</small>
                                <p class="mb-0 mt-1">{{ $comentari->comentario }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Aún no hay opiniones sobre este destino.</p>
                    @endforelse

                    {{-- LÓGICA DE PUBLICACIÓN SEGÚN RESERVA --}}
                    @auth
                        @if($usuarioHaReservado)
                            <form action="{{ route('comentario.store') }}" method="POST" class="mt-4 bg-light p-3 rounded shadow-sm border">
                                @csrf
                                <input type="hidden" name="idvacacion" value="{{ $vacacion->id }}">
                                <div class="mb-3">
                                    <label for="comentario" class="form-label fw-bold">Cuéntanos tu experiencia</label>
                                    <textarea name="comentario" id="comentario" rows="3" class="form-control" placeholder="¿Qué te pareció este viaje?"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Publicar comentario</button>
                            </form>
                        @else
                            <div class="alert alert-info mt-4 border-0 shadow-sm">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                Solo los viajeros que han reservado este destino pueden dejar un comentario.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-secondary mt-4 border-0">
                            <i class="bi bi-lock-fill me-2"></i>
                            Debes iniciar sesión para comentar tus viajes reservados.
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Columna Derecha: Reserva --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Reservar este destino</h5>
                    
                    @auth
                        <form action="{{ route('reserva.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="idvacacion" value="{{ $vacacion->id }}">
                            
                            <div class="mb-3">
                                <label for="fecha_reserva" class="form-label">¿Cuándo quieres viajar?</label>
                                <input type="date" name="fecha_reserva" id="fecha_reserva" class="form-control" min="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg fw-bold">
                                    <i class="bi bi-calendar-check me-2"></i>Confirmar Reserva
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning mb-0 text-center">
                            <p class="mb-2">Para reservar este paquete debes estar identificado.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Iniciar Sesión</a>
                        </div>
                    @endauth

                    <div class="mt-4">
                        <small class="text-muted d-block mb-2 text-center">Garantía de cancelación gratuita</small>
                        <hr>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="bi bi-shield-check fs-4 me-2 text-success"></i>
                            <span>Pago seguro y verificado por VacacionesApp.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteCommentModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-bs-id');
                const texto = button.getAttribute('data-bs-texto');
                
                const modalBodyPreview = deleteModal.querySelector('#comment-text-preview');
                const form = deleteModal.querySelector('#deleteCommentForm');
                
                modalBodyPreview.textContent = texto;
                // Construcción de ruta absoluta para evitar 404 en subcarpetas
                form.action = "{{ url('comentario') }}/" + id;
            });
        }
    });
</script>
@endsection