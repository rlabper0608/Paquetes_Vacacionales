<?php

namespace App\Http\Controllers;

use App\Http\Requests\VacacionCreateRequest;
use App\Models\Foto;
use App\Models\Tipo;
use App\Models\Vacacion;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class VacacionController extends Controller {

    function __construct() {
        // $this->middleware('verified')->except(['index', 'pelo', 'show']);
    }

    public function index(): View {
        $vacaciones = Vacacion::with(['tipo', 'foto'])->paginate(6);
        return view('vacacion.index', ['vacaciones'=>$vacaciones]);
    }

    public function create(): View {
        $tipos = Tipo::all();
        return view('vacacion.create', ['tipos'=>$tipos]);
    }

    public function store(VacacionCreateRequest $request): RedirectResponse {
        $vacacion = new Vacacion($request->all());
        $result = false;

        try {
            $result = $vacacion->save();
            
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $file) {
                    $ruta = $file->store('fotos_vacaciones', 'public');
                    // Crear registro en la tabla 'foto'
                    $vacacion->foto()->create(['ruta' => $ruta]);
                }
            }
            
            $mensajetxt = "El paquete se ha añadido";
        } catch(UniqueConstraintViolationException $e) {
            $mensajetxt = "Llave primaria";
        } catch (QueryException $e) {
            $mensajetxt = "Valor nulo";
        } catch (\Exception $e) {
            $mensajetxt = "Error fatal". $e->getMessage();
        }

        $mensaje = [
            "mensajeTexto" => $mensajetxt,
        ];

        if ($result) {
            return redirect() -> route('main')->with($mensaje);
        } else {
            return back()->withInput()->withErrors($mensaje);
        }
    }

    public function show(Vacacion $vacacion): View {
        return view('vacacion.show', ['vacacion'=>$vacacion]);
    }

    public function edit(Vacacion $vacacion): View {
        $tipos = Tipo::all();
        return view('vacacion.edit', ['vacacion' => $vacacion, 'tipos' => $tipos]);
    }

    public function update(Request $request, Vacacion $vacacion): RedirectResponse {
        $result = false;

        $vacacion->fill($request->all());

        try {
            // Controlamos el borrado de fotos
            if ($request->has('eliminar_fotos')) {
                foreach ($request->eliminar_fotos as $fotoId) {
                    $foto = \App\Models\Foto::find($fotoId);
                    if ($foto) {
                        // Borrar el archivo físico del disco 'public'
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($foto->ruta);
                        // Borrar el registro de la BD
                        $foto->delete();
                    }
                }
            }
            // Añade fotos nuevas
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $file) {
                    $ruta = $file->store('fotos_vacaciones', 'public');
                    $vacacion->foto()->create(['ruta' => $ruta]);
                }
            }

            $result = $vacacion->save();
            $mensajetxt = "El paquete se ha actualizado correctamente.";
        } catch(UniqueConstraintViolationException $e) {
            $mensajetxt = "Llave primaria";
        } catch (QueryException $e) {
            $mensajetxt = "Valor nulo";
        } catch (\Exception $e) {
            $mensajetxt = "Error fatal". $e->getMessage();
        }

        $mensaje = [
            "mensajeTexto" => $mensajetxt,
        ];

        if ($result) {
            return redirect() -> route('main')->with($mensaje);
        } else {
            return back()->withInput()->withErrors($mensaje);
        }
    }

    public function destroy(Vacacion $vacacion): RedirectResponse {

        try{
            $result = $vacacion->delete();
            $mensajetxt='El paquete se ha eliminado correctamente';
        }
        catch(\Exception $e){
            $result = false;
            $mensajetxt='El paquete no se ha eliminado';
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
