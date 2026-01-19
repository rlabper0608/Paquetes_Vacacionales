@extends('app.template')

@section('title', 'Reserva Confirmada')

@section('content')
<div class="container text-center py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            {{-- Icono Animado de Éxito --}}
            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
            </div>
            
            <h1 class="fw-bold mb-3">¡Reserva confirmada con éxito!</h1>
            <p class="lead text-muted mb-5">
                ¡Enhorabuena, <strong>{{ Auth::user()->name }}</strong>! Tu aventura en <strong>{{ $reserva->vacacion->titulo }}</strong> ya está programada. 
                Hemos enviado los detalles a tu correo electrónico.
            </p>

            <div class="card border-0 shadow-sm bg-light mb-5">
                <div class="card-body p-4 text-start">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">Resumen del viaje</h5>
                    <div class="row">
                        <div class="col-6 mb-2 text-muted small uppercase fw-bold">Referencia:</div>
                        <div class="col-6 mb-2 fw-bold text-primary">#RSV-{{ $reserva->id }}</div>
                        
                        <div class="col-6 mb-2 text-muted small uppercase fw-bold">Fecha del viaje:</div>
                        <div class="col-6 mb-2">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</div>
                        
                        <div class="col-6 mb-2 text-muted small uppercase fw-bold">Destino:</div>
                        <div class="col-6 mb-2">{{ $reserva->vacacion->titulo }}</div>

                        <div class="col-6 mb-2 text-muted small uppercase fw-bold">Precio total:</div>
                        <div class="col-6 mb-2 h5 mb-0 text-success">{{ number_format($reserva->vacacion->precio, 2) }}€</div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('reserva.index') }}" class="btn btn-primary px-4">
                    <i class="bi bi-list-check me-2"></i>Ver mis reservas
                </a>
                <a href="{{ route('vacacion.index') }}" class="btn btn-outline-secondary px-4">
                    Seguir explorando
                </a>
            </div>
        </div>
    </div>
</div>
@endsection