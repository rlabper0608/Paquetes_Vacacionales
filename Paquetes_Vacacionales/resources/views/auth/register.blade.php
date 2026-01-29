@extends('app.template')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5">
                    {{-- Encabezado --}}
                    <div class="text-center mb-4">
                        <div class="bg-success bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-passport fs-1"></i>
                        </div>
                        <h2 class="fw-bold text-dark">{{ __('Crea tu cuenta') }}</h2>
                        <p class="text-muted small">Únete para empezar a planificar tus próximas vacaciones</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Nombre --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold text-secondary small">{{ __('Nombre Completo') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                <input id="name" type="text" class="form-control bg-light border-start-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Tu nombre">
                            </div>
                            @error('name')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold text-secondary small">{{ __('Correo Electrónico') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="ejemplo@correo.com">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold text-secondary small">{{ __('Contraseña') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-lock"></i></span>
                                <input id="password" type="password" class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres">
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-semibold text-secondary small">{{ __('Confirmar Contraseña') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-check2-circle"></i></span>
                                <input id="password-confirm" type="password" class="form-control bg-light border-start-0" name="password_confirmation" required autocomplete="new-password" placeholder="Repite tu contraseña">
                            </div>
                        </div>

                        {{-- Botón Registro --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg shadow-sm fw-bold py-2 rounded-3">
                                <i class="bi bi-person-plus-fill me-2"></i> {{ __('Registrarme') }}
                            </button>
                        </div>

                        {{-- Link a Login --}}
                        <div class="text-center mt-4">
                            <p class="small text-muted mb-0">¿Ya tienes cuenta? 
                                <a href="{{ route('login') }}" class="fw-bold text-decoration-none text-success">{{ __('Inicia sesión aquí') }}</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f0f2f5; }
    .form-control:focus { box-shadow: none; border-color: #198754; }
    .card { border-radius: 1.5rem; }
    .input-group-text { border-color: #dee2e6; }
</style>
@endsection