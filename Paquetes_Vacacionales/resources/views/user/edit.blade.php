@extends('app.template')

@section('title', 'Editar Usuario: ' . $user->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow border-0">
            <div class="card-header bg-warning py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-shield-lock-fill me-2"></i>Gestionar Perfil y Permisos</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre del Usuario</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                    </div>

                    {{-- SOLO VISIBLE PARA ADMINISTRADORES --}}
                    @if(Auth::user()->rol == 'admin')
                        <div class="mb-4 p-3 bg-light rounded border">
                            <label for="rol" class="form-label fw-bold text-primary">Nivel de Acceso (Rol)</label>
                            <select name="rol" id="rol" class="form-select shadow-sm">
                                <option value="user" {{ old('rol', $user->rol) == 'user' ? 'selected' : '' }}>Usuario (Cliente)</option>
                                <option value="advanced" {{ old('rol', $user->rol) == 'advanced' ? 'selected' : '' }}>Avanzado (Gestor)</option>
                                <option value="admin" {{ old('rol', $user->rol) == 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                            
                            <hr>

                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="verified" id="verified" value="1" {{ $user->email_verified_at ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="verified">
                                    Correo Verificado
                                </label>
                            </div>
                            <div class="form-text mt-2 italic">Si desactivas esto, el usuario tendrá que volver a verificar su email.</div>
                        </div>
                    @else
                        {{-- Campo oculto para mantener el rol actual si el usuario se edita a sí mismo --}}
                        <input type="hidden" name="rol" value="{{ $user->rol }}">
                    @endif

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold text-secondary">Nueva Contraseña</label>
                        <input type="password" name="password" id="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Dejar en blanco para mantener la actual">
                        @error('password')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold text-secondary">Confirmar Nueva Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                            class="form-control" 
                            placeholder="Repite la nueva contraseña">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ Auth::user()->rol == 'admin' ? route('user.index') : route('main') }}" class="btn btn-light border">Volver</a>
                        <button type="submit" class="btn btn-warning px-4 fw-bold">Actualizar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection