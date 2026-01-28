<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComentarioCreateRequest;
use App\Models\Comentario;
use App\Models\Reserva;
use App\Models\Vacacion;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ComentarioController extends Controller {

    // Vers listado de comentarios (no es necesario)
    function index(): View {
        $comentarios = Comentario::with(['usuario', 'vacacion'])->paginate(15);
        return view('comentario.index', ['comentarios' => $comentarios]);
    }

    // Mandar al formulario de creación de un comentario (no es neceario)
    function create(): View {
        $vacaciones = Vacacion::all();
        return view('comentario.create', ['vacaciones' => $vacaciones]);
    }

    // Guardar comentario en la base de datos
    function store(ComentarioCreateRequest $request): RedirectResponse {
        
        // Control para que un usuario al modificar el html(F12) no pueda comentar en un paquete que no ha reservado
        $userId = auth()->id();
        $vacacionId = $request->idvacacion;

        $tieneReserva = Reserva::where('iduser', $userId)->where('idvacacion', $vacacionId)->exists();

        // Si tiene reserva si comenta
        if($tieneReserva) {
            $comentario = new Comentario($request->all());
            $comentario->iduser = Auth::id();

            $result = false;

            try {
                $result = $comentario->save();
                $mensajetxt = "Tu comentario ha sido publicado";
            } catch(UniqueConstraintViolationException $e) {
                $mensajetxt = "Error: Comentario duplicado";
            } catch (QueryException $e) {
                $mensajetxt = "Error: No se pudo guardar el comentario" .$e;
            } catch (\Exception $e) {
                $mensajetxt = "Error fatal: ";
            }

            $mensaje = [
                "mensajeTexto" => $mensajetxt,
            ];

            if ($result) {
                return back()->with($mensaje);
            } else {
                return back()->withInput()->withErrors($mensaje);
            }
        } else {
            // Si no la tiene se le indica el error y se le devuelve a la página
            $mensaje = [
                "mensajeTexto" => "No puedes realizar un comentario en un paquete que no has reservado"
            ];
            return back()->withInput()->withErrors($mensaje);
        }
        
        
    }

    // Ver un comentario de manera individual (no es necesario)
    function show(Comentario $comentario): View {
        return view('comentario.show', ['comentario' => $comentario]);
    }

    // Mandar a la edición de un comentario
    function edit(Comentario $comentario): View {
        return view('comentario.edit', ['comentario' => $comentario]);
    }

    // Guardar los cambios realizados en la base de datos
    function update(Request $request, Comentario $comentario): RedirectResponse {
        $result = false;

        $comentario->fill($request->all());

        try {
            $result = $comentario->save();
            $mensajetxt = "El comentario ha sido actualizado.";
        } catch(\Exception $e) {
            $result = false;
            $mensajetxt = "Error al editar el comentario";
        }

        $mensaje = [
            "mensajeTexto" => $mensajetxt,
        ];

        if ($result) {
            return redirect()->route('vacacion.show', $comentario->idvacacion)->withMessage($mensaje);
        } else {
            return back()->withInput()->withErrors($mensaje);
        }
    }

    // Borrar el comentario de la base de datos
    function destroy(Comentario $comentario): RedirectResponse {
        try {
            $result = $comentario->delete();
            $mensajetxt = 'Comentario eliminado correctamente';
        }
        catch(\Exception $e){
            $result = false;
            $mensajetxt = 'No se ha podido eliminar el comentario';
        }

        $mensaje = [
            'mensajeTexto' => $mensajetxt,
        ];

        if($result){
            return back()->with($mensaje);
        } else {
            return back()->withErrors($mensaje);
        }
    }
}