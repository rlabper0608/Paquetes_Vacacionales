<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Vacacion;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FotoController extends Controller {

    public function index(): View {
        $fotos = Foto::with('vacacion')->paginate(12);
        return view('foto.index', ['fotos' => $fotos]);
    }

    public function create(): View {
        $vacaciones = Vacacion::all();
        return view('foto.create', ['vacaciones' => $vacaciones]);
    }

    public function store(Request $request): RedirectResponse {
        $foto = new Foto($request->all());
        $result = false;

        try {
            if ($request->hasFile('ruta')) {
                // Guardamos el archivo y actualizamos el atributo ruta del objeto
                $rutaArchivo = $request->file('ruta')->store('fotos_vacaciones', 'public');
                $foto->ruta = $rutaArchivo;
            }

            $result = $foto->save();
            $mensajetxt = "La foto se ha añadido";
        } catch(UniqueConstraintViolationException $e) {
            $mensajetxt = "Llave primaria";
        } catch (QueryException $e) {
            $mensajetxt = "Valor nulo";
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

    public function show(Foto $foto): View {
        return view('foto.show', ['foto' => $foto]);
    }

    public function edit(Foto $foto): View {
        $vacaciones = Vacacion::all();
        return view('foto.edit', ['foto' => $foto, 'vacaciones' => $vacaciones]);
    }

    public function update(Request $request, Foto $foto): RedirectResponse {
        $result = false;

        $foto->fill($request->all()); 

        try {
            if ($request->hasFile('ruta_nueva')) {
                Storage::disk('public')->delete($foto->getOriginal('ruta'));
                $foto->ruta = $request->file('ruta_nueva')->store('fotos_vacaciones', 'public');
            }

            $result = $foto->save();
            $mensajetxt = "La foto se ha actualizado correctamente.";
        } catch(UniqueConstraintViolationException $e) {
            $mensajetxt = "Llave primaria";
        } catch (QueryException $e) {
            $mensajetxt = "Valor nulo";
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

    public function destroy(Foto $foto): RedirectResponse {
        try {
            // Borrado físico antes del delete
            Storage::disk('public')->delete($foto->ruta);
            
            $result = $foto->delete();
            $mensajetxt = 'La foto se ha eliminado correctamente';
        }
        catch(\Exception $e){
            $result = false;
            $mensajetxt = 'La foto no se ha eliminado';
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