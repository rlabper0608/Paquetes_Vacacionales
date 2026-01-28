@extends('app.template')

@section('title', 'Registrar Nuevo Usuario')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>Registrar Nuevo Usuario</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nombre Completo</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rol" class="form-label fw-bold">Asignar Rol</label>
                                <select name="rol" id="rol" class="form-select">
                                    @foreach($roles as $rol)
                                        <option value="{{ $rol }}">{{ ucfirst($rol) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold">Contraseña</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3 p-3 bg-light rounded border">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="verified" id="verified" value="1" checked>
                                <label class="form-check-label fw-bold" for="verified">
                                    Marcar correo como verificado
                                </label>
                            </div>
                            <div class="form-text">Si se activa, el usuario no recibirá el email de verificación y podrá entrar directamente.</div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="bi bi-check-circle me-1"></i> Crear Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection