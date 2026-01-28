<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoCreateRequest;
use App\Models\Tipo;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TipoController extends Controller {

    // Listado de los tipos
    function index(): View {
        $tipos = Tipo::all();
        return view('tipo.index', ['tipos' => $tipos]);
    }

    // Mandar al formulario de creación de un tipo
    function create(): View {
        return view('tipo.create');
    }

    // Guardar un tipo en la base de datos
    function store(TipoCreateRequest $request): RedirectResponse {
        $tipo = new Tipo($request->all());
        $result = false;

        try {
            $result = $tipo->save();
            $mensajetxt = "La categoría se ha añadido correctamente";
        } catch(UniqueConstraintViolationException $e) {
            $mensajetxt = "Error: Esa categoría ya existe (Llave primaria/única)";
        } catch (QueryException $e) {
            $mensajetxt = "Error: Valor nulo o error de base de datos";
        } catch (\Exception $e) {
            $mensajetxt = "Error fatal: " . $e->getMessage();
        }

        $mensaje = [
            "mensajeTexto" => $mensajetxt,
        ];

        if ($result) {
            return redirect()->route('main')->with($mensaje);
        } else {
            return back()->withInput()->withErrors($mensaje);
        }
    }

    // Ver un tipo en concreto
    function show(Tipo $tipo): View {
        return view('tipo.show', ['tipo' => $tipo]);
    }

    // Mandar al formulario de edición de un tipo
    function edit(Tipo $tipo): View {
        return view('tipo.edit', ['tipo' => $tipo]);
    }

    // Actualizar la información del tipo en la base de datos
    function update(Request $request, Tipo $tipo): RedirectResponse {
        $result = false;

        $tipo->fill($request->all());

        try {
            $result = $tipo->save();
            $mensajetxt = "La categoría se ha actualizado correctamente.";
        } catch(UniqueConstraintViolationException $e) {
            $mensajetxt = "Error: El nombre ya está en uso";
        } catch (QueryException $e) {
            $mensajetxt = "Error: Valor nulo";
        } catch (\Exception $e) {
            $mensajetxt = "Error fatal";
        }

        $mensaje = [
            "mensajeTexto" => $mensajetxt,
        ];

        if ($result) {
            return redirect()->route('main')->with($mensaje);
        } else {
            return back()->withInput()->withErrors($mensaje);
        }
    }

    // Borrar un tipo
    function destroy(Tipo $tipo): RedirectResponse  {
        try {
            // 1. Obtenemos todas las vacaciones de este tipo
            $vacacionesAsociadas = $tipo->vacacion; // Asumiendo que tienes la relación hasMany en el modelo Tipo

            foreach ($vacacionesAsociadas as $vacacion) {
                // A. Borrar archivos físicos de las fotos de cada vacación
                foreach ($vacacion->foto as $foto) {
                    if (Storage::disk('public')->exists($foto->ruta)) {
                        Storage::disk('public')->delete($foto->ruta);
                    }
                }
                
                // B. Borrar hijos de la vacación (fotos, comentarios, reservas en DB)
                $vacacion->foto()->delete();
                $vacacion->comentario()->delete();
                $vacacion->reserva()->delete();

                // C. Borrar la vacación en sí
                $vacacion->delete();
            }

            // 2. Una vez limpia la base de datos de hijos, borramos el Tipo
            $result = $tipo->delete();
            $mensajetxt = 'La categoría y todos los viajes asociados se han eliminado correctamente';

        } catch (\Exception $e) {
            $result = false;
            $mensajetxt = 'Error fatal al eliminar: ' . $e->getMessage();
        }

        $mensaje = ['mensajeTexto' => $mensajetxt];

        if ($result) {
            return redirect()->route('tipo.index')->with($mensaje); // Mejor volver a la lista de tipos
        } else {
            return back()->withErrors($mensaje);
        }
    }
}