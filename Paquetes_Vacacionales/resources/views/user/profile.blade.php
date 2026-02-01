@extends('app.template')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white fw-bold border-bottom py-3">
                    <i class="bi bi-person-gear me-2 text-primary"></i>Configuración de Mi Perfil
                </div>
                <div class="card-body p-4">
                    
                    {{-- Estado de Verificación --}}
                    <div class="alert {{ $user->email_verified_at ? 'alert-success' : 'alert-warning' }} d-flex align-items-center border-0 shadow-sm mb-4">
                        <i class="bi {{ $user->email_verified_at ? 'bi-patch-check-fill' : 'bi-exclamation-triangle-fill' }} fs-4 me-3"></i>
                        <div>
                            @if($user->email_verified_at)
                                <span class="fw-bold">Cuenta Verificada.</span> Tu email fue confirmado el {{ $user->email_verified_at->format('d/m/Y') }}.
                            @else
                                <strong>Cuenta no verificada.</strong> Algunas funciones están limitadas.
                                <form action="{{ route('verification.resend') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 ms-2 small fw-bold">Reenviar enlace de verificación</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h5 class="mb-3 text-secondary border-bottom pb-2">Información Personal</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                                @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Rol de usuario</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-shield-lock"></i></span>
                                <input type="text" class="form-control bg-light" value="{{ strtoupper($user->rol) }}" readonly>
                            </div>
                            <small class="text-muted">El rol no puede ser modificado por el usuario.</small>
                        </div>

                        {{-- SECCIÓN DE CONTRASEÑA --}}
                        <h5 class="mb-3 text-secondary border-bottom pb-2 mt-5">Cambiar Contraseña</h5>
                        <p class="text-muted small mb-3">Si no deseas cambiar tu contraseña, deja estos campos en blanco.</p>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Nueva Contraseña</label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mínimo 8 caracteres">
                                @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Repite la contraseña">
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="bi bi-save me-2"></i>Actualizar Perfil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection