@extends('app.template')

@section('title', 'Mis Reservas')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">
                    <i class="bi bi-calendar-check text-primary"></i> 
                    {{ Auth::user()->rol == 'admin' ? 'Gestión Global de Reservas' : 'Mis Viajes Reservados' }}
                </h2>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Ref.</th>
                                    @if(Auth::user()->rol == 'admin')
                                        <th>Cliente</th>
                                    @endif
                                    <th>Destino</th>
                                    <th>Fecha del Viaje</th>
                                    <th>Precio</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservas as $reserva)
                                <tr>
                                    <td class="ps-4 text-muted small">#RSV-{{ $reserva->id }}</td>
                                    
                                    @if(Auth::user()->rol == 'admin')
                                        <td>
                                            <div class="fw-bold">{{ $reserva->user->name }}</div>
                                            <div class="small text-muted">{{ $reserva->user->email }}</div>
                                        </td>
                                    @endif

                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($reserva->vacacion->foto->count() > 0)
                                                <img src="{{ asset('storage/' . $reserva->vacacion->foto->first()->ruta) }}" 
                                                     class="rounded me-2" style="width: 50px; height: 40px; object-fit: cover;">
                                            @endif
                                            <span class="fw-bold">{{ $reserva->vacacion->titulo }}</span>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}
                                        </span>
                                    </td>

                                    <td class="fw-bold text-primary">
                                        {{ number_format($reserva->vacacion->precio, 2) }}€
                                    </td>

                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="{{ route('vacacion.show', $reserva->idvacacion) }}" class="btn btn-sm btn-outline-primary" title="Ver destino">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            <form action="{{ route('reserva.destroy', $reserva->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Deseas cancelar esta reserva?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancelar reserva">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->rol == 'admin' ? '6' : '5' }}" class="text-center py-5">
                                        <i class="bi bi-luggage fs-1 text-muted d-block mb-3"></i>
                                        <p class="text-muted">No hay ninguna reserva registrada.</p>
                                        <a href="{{ route('vacacion.index') }}" class="btn btn-primary btn-sm">Explorar destinos</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection