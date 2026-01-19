@extends('app.template')

@section('title', 'Almacén Global de Fotos')

@section('content')
<div class="container">
    <h2 class="fw-bold mb-4">Todas las Fotos del Sistema</h2>

    <div class="table-responsive bg-white shadow-sm rounded">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Imagen</th>
                    <th>Pertenece a</th>
                    <th>Ruta del archivo</th>
                    <th class="text-end">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fotos as $foto)
                <tr>
                    <td>
                        <img src="{{ asset('storage/' . $foto->ruta) }}" class="rounded" style="width: 60px;">
                    </td>
                    <td>{{ $foto->vacacion->titulo }}</td>
                    <td class="small text-muted">{{ $foto->ruta }}</td>
                    <td class="text-end">
                        <form action="{{ route('foto.destroy', $foto->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-link text-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection