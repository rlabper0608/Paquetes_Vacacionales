<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Vacacion;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ComentarioController extends Controller {

    public function index(): View {
        $comentarios = Comentario::with(['usuario', 'vacacion'])->paginate(15);
        return view('comentario.index', ['comentarios' => $comentarios]);
    }

    public function create(): View {
        $vacaciones = Vacacion::all();
        return view('comentario.create', ['vacaciones' => $vacaciones]);
    }

    public function store(Request $request): RedirectResponse {
        $comentario = new Comentario($request->all());
        $comentario->iduser = Auth::id();
        
        $result = false;

        try {
            $result = $comentario->save();
            $mensajetxt = "Tu comentario ha sido publicado";
        } catch(UniqueConstraintViolationException $e) {
            $mensajetxt = "Error: Comentario duplicado";
        } catch (QueryException $e) {
            $mensajetxt = "Error: No se pudo guardar el comentario";
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
    }

    public function show(Comentario $comentario): View {
        return view('comentario.show', ['comentario' => $comentario]);
    }

    public function edit(Comentario $comentario): View {
        return view('comentario.edit', ['comentario' => $comentario]);
    }

    public function update(Request $request, Comentario $comentario): RedirectResponse {
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

    public function destroy(Comentario $comentario): RedirectResponse {
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