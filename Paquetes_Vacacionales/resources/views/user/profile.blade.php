@extends('app.template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Mi Perfil</div>
                <div class="card-body">
                    
                    <div class="alert {{ $user->email_verified_at ? 'alert-success' : 'alert-warning' }} d-flex align-items-center">
                        <i class="bi {{ $user->email_verified_at ? 'bi-patch-check-fill' : 'bi-exclamation-triangle-fill' }} me-2"></i>
                        <div>
                            @if($user->email_verified_at)
                                Usuario verificado el {{ $user->email_verified_at->format('d/m/Y') }}
                            @else
                                <strong>Cuenta no verificada.</strong> Revisa tu correo o solicita un nuevo enlace.
                                <form action="{{ route('verification.resend') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 ms-2 small">Reenviar verificación</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol actual</label>
                            <input type="text" class="form-control bg-light" value="{{ $user->rol }}" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection