@extends('app.template') {{-- O el nombre exacto de tu layout principal --}}

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
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                             <label for="rol" class="form-label fw-bold">Asignar Rol</label>
                             <select name="rol" id="rol" class="form-select">
                                 @foreach($roles as $rol)
                                     <option value="{{ $rol }}">{{ $rol }}</option>
                                 @endforeach
                             </select>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Contraseña Temporal</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            <div class="form-text">Mínimo 8 caracteres.</div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary fw-bold">
                                Crear Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection