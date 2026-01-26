<?php

namespace App\Http\Controllers;

use App\Http\Requests\VacacionCreateRequest;
use App\Models\Foto;
use App\Models\Reserva;
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
        $this->middleware('verified')->except(['index', 'show']);
    }

    function getField (?string $str): string {
        $values = [
            1 => 'vacacion.id',
            2 => 'vacacion.precio', 
            3 => 'tipo.nombre', 
        ];
        return $this->getParam($str, $values);
    }

    function getOrder (string|null $str): string {
        $values = [
            1 => 'asc',
            2 => 'desc'
        ];

        return $this->getParam($str, $values);
    }

    function getParam(?string $str, array $values): string {
        $result = $values[1];

        if(isset($values[$str])) {
            $result = $values[$str];
        }
        return $result;
    }

    public function index(Request $request): View {
        $vacaciones = Vacacion::with(['tipo', 'foto']);
        
        $field = $this->getField($request->field);
        $order = $this->getOrder($request->order);
        $idtipo = $request->idtipo;
        $desde = $request->desde;
        $hasta = $request->hasta;
        $q = $request->q;

            //Construir la consulta paso a paso
            $query = Vacacion::query();
            
            //Le unimos la tabla tipo para poder acceder a su nombre
            $query ->join('tipo','vacacion.idtipo', '=', 'tipo.id');
            
            //Reemplazo el asterisco por los campos que quiero obtener
            $query->select('vacacion.*','tipo.nombre');
            
            //Filtro idtipo
            if($idtipo != null){
                $query->where('idtipo','=',$idtipo);
            }

            // Filtro desde
            if($desde != null){
                $query->where('precio','>=',$desde);
            }

            // Filtro hasta
            if($hasta != null){
                $query->where('precio','<=',$hasta);
            }

            if($q != null) {
                $query->where(function($subquery) use ($q) {
                    $subquery
                        ->where('vacacion.id', 'like', "%q%")
                        ->orWhere('vacacion.titulo', 'like', '%' . $q . '%')
                        ->orWhere('vacacion.descripcion', 'like', '%' . $q . '%')
                        ->orWhere('vacacion.precio', 'like', '%' . $q . '%')
                        ->orWhere('tipo.nombre', 'like', '%' . $q . '%');
                });
            }
        
        
        $query->orderBy($field,$order);
        $vacaciones = $query->paginate(6)->withQueryString();
        $tipos = Tipo::all();

        return view('vacacion.index', [
            'vacaciones'=>$vacaciones,
            'tipos'=>$tipos,
            'desde' => $desde,
            'hasta' => $hasta,
            'q' => $q,
            'hasPagination' => true,
            'idtipo' => 'idtipo'
            ]);
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
        $reservas = Reserva::all();
        $usuarioHaReservado = false;

            $usuarioHaReservado = Reserva::where('iduser', auth()->id())
                ->where('idvacacion', $vacacion->id)
                ->exists();
        
        return view('vacacion.show', ['vacacion'=>$vacacion, 'usuarioHaReservado' => $usuarioHaReservado, 'reservas' => $reservas]);
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

       try {
        // 1. Borrar archivos físicos de las fotos del disco
        foreach ($vacacion->foto as $foto) {
            if (Storage::disk('public')->exists($foto->ruta)) {
                Storage::disk('public')->delete($foto->ruta);
            }
        }

        // 2. Borrar los registros hijos manualmente si no tienes "onDelete cascade"
        $vacacion->foto()->delete();      // Borra registros en tabla fotos
        $vacacion->comentario()->delete(); // Borra registros en tabla comentarios
        $vacacion->reserva()->delete();    // Borra registros en tabla reservas (¡Cuidado aquí!)

        // 3. Ahora sí, borrar el paquete vacacional
        $result = $vacacion->delete();
        $mensajetxt = 'El paquete y todos sus datos asociados se han eliminado correctamente';
        
    } catch (\Exception $e) {
        $result = false;
        // Agregamos el mensaje de error real para saber qué falla si sigue sin borrar
        $mensajetxt = 'Error al eliminar: ' . $e->getMessage();
    }

    $mensaje = ['mensajeTexto' => $mensajetxt];

    if ($result) {
        // Redirigimos a la lista de gestión, no al main, para ver el cambio
        return redirect()->route('vacacion.lista')->with($mensaje);
    } else {
        return back()->withErrors($mensaje);
    }
    }

    public function lista() {
    $vacaciones = Vacacion::with('tipo')->get();
    return view('vacacion.lista', ['vacaciones' => $vacaciones ]);
}
}
