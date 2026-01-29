@extends('app.template')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            {{-- Mensaje de Bienvenida --}}
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-primary">¡Hola, {{ Auth::user()->name }}!</h1>
                <p class="lead text-muted">Bienvenido a tu panel de gestión de viajes.</p>
                <hr class="mx-auto" style="width: 100px; border-top: 3px solid #0d6efd;">
            </div>

            @if (session('status'))
                <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                </div>
            @endif

            <div class="row g-4">
                {{-- Card: Mis Reservas (Para todos) --}}
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                        <div class="card-body p-4 text-center">
                            <div class="icon-shape bg-primary-subtle text-primary rounded-circle mb-3 mx-auto" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-briefcase-fill fs-2"></i>
                            </div>
                            <h3 class="card-title h5">Mis Reservas</h3>
                            <p class="text-muted">Consulta el historial de tus viajes y los detalles de tus próximas vacaciones.</p>
                            <a href="{{ route('reserva.index') }}" class="btn btn-primary px-4">Ir a mis viajes</a>
                        </div>
                    </div>
                </div>

                {{-- Card: Perfil --}}
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                        <div class="card-body p-4 text-center">
                            <div class="icon-shape bg-success-subtle text-success rounded-circle mb-3 mx-auto" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person-badge-fill fs-2"></i>
                            </div>
                            <h3 class="card-title h5">Mi Perfil</h3>
                            <p class="text-muted">Gestiona tus datos personales, contraseña y estado de verificación.</p>
                            <a href="{{ route('user.profile') }}" class="btn btn-outline-success px-4">Editar Perfil</a>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->rol === 'admin')
                <div class="col-12">
                    <div class="card border-0 bg-dark text-white shadow-lg">
                        <div class="card-body p-4 d-md-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-shield-lock-fill fs-1 me-4 text-warning"></i>
                                <div>
                                    <h3 class="h4 mb-1">Panel de Administración</h3>
                                    <p class="mb-0 text-white-50">Tienes acceso a la gestión total de paquetes, tipos y reservas de clientes.</p>
                                </div>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <a href="{{ route('vacacion.lista') }}" class="btn btn-warning me-2">Gestionar Paquetes</a>
                                <a href="{{ route('reserva.reservas') }}" class="btn btn-outline-warning">Ver Todas las Reservas</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .transition {
        transition: all 0.3s ease-in-out;
    }
    .bg-primary-subtle { background-color: #cfe2ff; }
    .bg-success-subtle { background-color: #d1e7dd; }
</style>
@endsection