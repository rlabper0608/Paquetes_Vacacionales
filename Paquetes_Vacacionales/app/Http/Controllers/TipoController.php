<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TipoController extends Controller {

    public function index(): View {
        $tipos = Tipo::all();
        return view('tipo.index', ['tipos' => $tipos]);
    }

    public function create(): View {
        return view('tipo.create');
    }

    public function store(Request $request): RedirectResponse {
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

    public function show(Tipo $tipo): View {
        return view('tipo.show', ['tipo' => $tipo]);
    }

    public function edit(Tipo $tipo): View {
        return view('tipo.edit', ['tipo' => $tipo]);
    }

    public function update(Request $request, Tipo $tipo): RedirectResponse {
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

    public function destroy(Tipo $tipo): RedirectResponse {
        try {
            $result = $tipo->delete();
            $mensajetxt = 'La categoría se ha eliminado correctamente';
        }
        catch(\Exception $e){
            $result = false;
            $mensajetxt = 'No se puede eliminar: hay vacaciones asociadas a esta categoría';
        }

        $mensaje = [
            'mensajeTexto' => $mensajetxt,
        ];

        if($result){
            return redirect()->route('main')->with($mensaje);
        } else {
            return back()->withInput()->withErrors($mensaje);
        }
    }
}