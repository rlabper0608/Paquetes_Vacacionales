@extends('app.template')

@section('title', 'Bienvenido a VacacionesApp')

@section('content')
{{-- Hero Section --}}
<div class="bg-primary text-white py-5 mb-5 shadow-sm" style="background: linear-gradient(45deg, #0d6efd 0%, #00d4ff 100%);">
    <div class="container py-5 text-center">
        <h1 class="display-3 fw-bold mb-3">Descubre tu próximo destino</h1>
        <p class="lead mb-4">Los mejores paquetes vacacionales al mejor precio. Playa, montaña, cultura y aventura te esperan.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="{{ route('vacacion.index') }}" class="btn btn-light btn-lg px-4 gap-3 fw-bold text-primary">
                Ver Destinos
            </a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">Registrarse</a>
            @endguest
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4 text-center">
        {{-- Característica 1 --}}
        <div class="col-md-4">
            <div class="p-4 border rounded-3 bg-white shadow-sm h-100">
                <i class="bi bi-shield-check text-primary display-5 mb-3"></i>
                <h3 class="fw-bold">Reserva Segura</h3>
                <p class="text-muted">Gestión de reservas verificada y garantizada por nuestro equipo.</p>
            </div>
        </div>
        {{-- Característica 2 --}}
        <div class="col-md-4">
            <div class="p-4 border rounded-3 bg-white shadow-sm h-100">
                <i class="bi bi-chat-heart text-primary display-5 mb-3"></i>
                <h3 class="fw-bold">Opiniones Reales</h3>
                <p class="text-muted">Lee los comentarios de otros viajeros y comparte tu propia experiencia.</p>
            </div>
        </div>
        {{-- Característica 3 --}}
        <div class="col-md-4">
            <div class="p-4 border rounded-3 bg-white shadow-sm h-100">
                <i class="bi bi-geo-alt text-primary display-5 mb-3"></i>
                <h3 class="fw-bold">Variedad Total</h3>
                <p class="text-muted">Desde escapadas culturales hasta relax total en playas paradisíacas.</p>
            </div>
        </div>
    </div>
</div>

{{-- Sección de llamada a la acción --}}
<div class="container py-4">
    <div class="p-5 mb-4 bg-light rounded-3 border">
        <div class="container-fluid py-2">
            <h2 class="display-6 fw-bold">¿Tienes dudas?</h2>
            <p class="col-md-8 fs-5">Nuestro equipo de atención al cliente está disponible para ayudarte a planificar el viaje de tus sueños. No esperes más para vivir una experiencia inolvidable.</p>
            <a href="{{ route('about') }}" class="btn btn-primary btn-lg">Contáctanos</a>
        </div>
    </div>
</div>
@endsection