@extends('app.template')

@section('title', 'Sobre Nosotros - VacacionesApp')

@section('content')
{{-- Encabezado de la página --}}
<div class="bg-dark text-white py-5 mb-5" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=1374&q=80'); background-size: cover; background-position: center;">
    <div class="container py-4 text-center">
        <h1 class="display-4 fw-bold">Nuestra Misión</h1>
        <p class="lead mx-auto" style="max-width: 700px;">Conectamos a viajeros con experiencias inolvidables, promoviendo un turismo responsable y accesible para todos.</p>
    </div>
</div>

<div class="container">
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h2 class="fw-bold mb-4">¿Por qué nació VacacionesApp?</h2>
            <p class="text-muted">
                Fundada en 2024, VacacionesApp nació de la necesidad de simplificar la búsqueda de paquetes vacacionales. Queríamos crear una plataforma donde la transparencia fuera lo primero: fotos reales, precios sin sorpresas y una comunidad activa que comparte sus experiencias.
            </p>
            <p class="text-muted">
                Creemos que viajar no es solo ver lugares nuevos, sino vivir historias. Por eso, seleccionamos cada destino y cada tipo de paquete pensando en la calidad y la satisfacción de nuestros clientes.
            </p>
            <div class="row g-3 mt-2">
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success me-2 fs-4"></i>
                        <span class="fw-bold">Destinos Exclusivos</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success me-2 fs-4"></i>
                        <span class="fw-bold">Soporte 24/7</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80" alt="Nuestro Equipo" class="img-fluid rounded-3 shadow">
        </div>
    </div>

    <hr class="my-5">

    {{-- Valores de la Empresa --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold">Nuestros Valores</h2>
        <p class="text-muted">Lo que nos mueve cada día</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4 text-center">
            <div class="p-4 shadow-sm rounded-3 bg-white h-100 border-top border-primary border-4">
                <i class="bi bi-heart-fill text-danger display-5 mb-3"></i>
                <h4 class="fw-bold">Pasión</h4>
                <p class="text-muted mb-0">Amamos lo que hacemos y eso se refleja en la atención personalizada que brindamos.</p>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div class="p-4 shadow-sm rounded-3 bg-white h-100 border-top border-primary border-4">
                <i class="bi bi-eye-fill text-primary display-5 mb-3"></i>
                <h4 class="fw-bold">Transparencia</h4>
                <p class="text-muted mb-0">Sin letras pequeñas. Lo que ves es lo que pagas y lo que vas a disfrutar.</p>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div class="p-4 shadow-sm rounded-3 bg-white h-100 border-top border-primary border-4">
                <i class="bi bi-globe text-success display-5 mb-3"></i>
                <h4 class="fw-bold">Sostenibilidad</h4>
                <p class="text-muted mb-0">Colaboramos con proveedores locales para minimizar el impacto ambiental.</p>
            </div>
        </div>
    </div>
</div>
@endsection