@extends('app.template')

@section('title', 'Perfil de ' . $user->name)

@section('content')
<div class="container">
    <div class="row g-4">
        {{-- Columna Izquierda: Información del Usuario --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-person-circle text-secondary" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    
                    @if($user->rol == 'admin')
                        <span class="badge bg-danger px-3 py-2">Administrador</span>
                    @elseif($user->rol == 'advanced')
                        <span class="badge bg-warning text-dark px-3 py-2">Usuario Avanzado</span>
                    @else
                        <span class="badge bg-info text-dark px-3 py-2">Cliente Estándar</span>
                    @endif
                    
                    <hr class="my-4">
                    
                    <div class="text-start">
                        <p class="small text-muted mb-1 text-uppercase fw-bold">Fecha de Registro</p>
                        <p class="mb-3"><i class="bi bi-calendar-event me-2"></i>{{ $user->created_at->format('d/m/Y') }}</p>
                        
                        <p class="small text-muted mb-1 text-uppercase fw-bold">Total Reservas</p>
                        <p class="mb-0"><i class="bi bi-airplane me-2"></i>{{ $user->reservas->count() }} viajes realizados</p>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-pencil me-1"></i> Editar Perfil
                        </a>
                        <a href="{{ route('user.index') }}" class="btn btn-light btn-sm border">
                            <i class="bi bi-arrow-left me-1"></i> Volver al