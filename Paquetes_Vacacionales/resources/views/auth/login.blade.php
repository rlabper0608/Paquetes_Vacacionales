@extends('app.template')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5">
                    {{-- Logo o Icono de Viajes --}}
                    <div class="text-center mb-4">
                        <div class="bg-primary bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-airplane-engines fs-1"></i>
                        </div>
                        <h2 class="fw-bold text-dark">{{ __('Iniciar Sesión') }}</h2>
                        <p class="text-muted small">Accede a tus paquetes vacacionales y reservas</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold text-secondary small">{{ __('Correo Electrónico') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="ejemplo@correo.com">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Contraseña --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold text-secondary small">{{ __('Contraseña') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                <input id="password" type="password" class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Recordarme y Olvido --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="remember">
                                    {{ __('Recordarme') }}
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none small fw-semibold" href="{{ route('password.request') }}">
                                    {{ __('¿Olvidaste tu clave?') }}
                                </a>
                            @endif
                        </div>

                        {{-- Botón de Acceso --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm fw-bold py-2 rounded-3">
                                {{ __('Ingresar') }}
                            </button>
                        </div>

                        {{-- Registro (Opcional) --}}
                        <div class="text-center mt-4">
                            <p class="small text-muted mb-0">¿No tienes cuenta? 
                                <a href="{{ route('register') }}" class="fw-bold text-decoration-none">{{ __('Regístrate aquí') }}</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- Footer del login --}}
            <p class="text-center mt-4 text-muted small">
                &copy; {{ date('Y') }} Tu Agencia de Viajes. Todos los derechos reservados.
            </p>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fa;
    }
    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }
    .input-group-text {
        border-color: #dee2e6;
    }
    .card {
        border-radius: 1.5rem;
    }
</style>
@endsection