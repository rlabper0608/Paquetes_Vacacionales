<?php

namespace App\Http\Controllers;

// Importación
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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class VacacionController extends Controller {

    function __construct() {
        $this->middleware('verified')->except(['index', 'show']);

        // Vamos a hacer otra barrera por si el usuario esta verificado y se sabe la ruta de creación por ejemplo, para que solo el admin pueda acceder
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->rol === 'admin') {
                // Nos deja pasar si soy el admin
                return $next($request);
            }

            // Si no es admin, lo mandamos a la home con un error
            return redirect('/')->withErrors(['mensajeTexto' => 'Acceso denegado. Solo administradores.']);
        })->except(['index', 'show']);
    }

    // Funciones para la ayuda del filtrado
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

    function index(Request $request): View {
        $vacaciones = Vacacion::with(['tipo', 'foto']);
        
        // Filtrado y ordenación
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

            // Filtro del search
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
        
        // Ordenación según los campos que hemos ido sacando antes
        $query->orderBy($field,$order);
        // Paginación
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

    // Función que te manda al formulario de creación de los paquetes vacacionales
    function create(): View {
        $tipos = Tipo::all();
        return view('vacacion.create', ['tipos'=>$tipos]);
    }

    // Guardamoas el paquete vacacional
    function store(VacacionCreateRequest $request): RedirectResponse {
        try {
            $vacacion = new Vacacion($request->all());
            $vacacion->save();

            // 1. Si el usuario subió fotos manualmente, las guardamos
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $file) {
                    $ruta = $file->store('fotos_vacaciones', 'public');
                    $vacacion->foto()->create(['ruta' => $ruta]);
                }
            } 
            // 2. Si NO subió fotos, buscamos una en Unsplash automáticamente
            else {
                $accessKey = 'TU_ACCESS_KEY_DE_UNSPLASH'; // Consíguela en unsplash.com/developers
                
                $response = Http::get('https://api.unsplash.com/search/photos', [
                    'query' => $vacacion->titulo, // Buscamos por el nombre del viaje
                    'client_id' => $accessKey,
                    'per_page' => 1,
                    'orientation' => 'landscape'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (!empty($data['results'])) {
                        $urlImagenExterna = $data['results'][0]['urls']['regular'];
                        
                        // Descargamos el contenido de la imagen
                        $contenidoImagen = file_get_contents($urlImagenExterna);
                        
                        // Generamos un nombre único para el archivo
                        $nombreArchivo = 'auto_' . Str::random(10) . '.jpg';
                        $rutaDestino = 'fotos_vacaciones/' . $nombreArchivo;

                        // Guardamos físicamente en el storage
                        Storage::disk('public')->put($rutaDestino, $contenidoImagen);

                        // Creamos el registro en la tabla 'foto'
                        $vacacion->foto()->create(['ruta' => $rutaDestino]);
                    }
                }
            }

            return redirect()->route('main')->with('mensajeTexto', 'Paquete creado con éxito (foto automática añadida)');
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

    // Muestra cada paquete de manera individual
    function show(Vacacion $vacacion): View {
        $reservas = Reserva::all();
        $usuarioHaReservado = false;

            $usuarioHaReservado = Reserva::where('iduser', auth()->id())
                ->where('idvacacion', $vacacion->id)
                ->exists();
        
        return view('vacacion.show', ['vacacion'=>$vacacion, 'usuarioHaReservado' => $usuarioHaReservado, 'reservas' => $reservas]);
    }

    // Nos manda al formulario de edición de un paquete individual
    function edit(Vacacion $vacacion): View {
        $tipos = Tipo::all();
        return view('vacacion.edit', ['vacacion' => $vacacion, 'tipos' => $tipos]);
    }

    // Guardamos los cambios del edit en la base de datos
    function update(Request $request, Vacacion $vacacion): RedirectResponse {
        $result = false;

        $vacacion->fill($request->all());

        try {
            // Controlamos el borrado de fotos, por si queremos quitar alguna
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

            // Control de insertado de imagenes nuevas, no tenemos que aplastar las anteriores, ya que tenemos un carrusel de imagenes y podemos tener tantas como queramos
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $file) {
                    $ruta = $file->store('fotos_vacaciones', 'public');
                    $vacacion->foto()->create(['ruta' => $ruta]);
                }
            }

            $result = $vacacion->save();
            $mensajetxt = "El paquete se ha actualizado correctamente.";

            // Control de errores
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

        // Redirección dependiendo del resultado
        if ($result) {
            return redirect() -> route('main')->with($mensaje);
        } else {
            return back()->withInput()->withErrors($mensaje);
        }
    }

    // Borramos un paquete vacacional
    function destroy(Vacacion $vacacion): RedirectResponse {

       try {
            // Borrramos las imagenes de la carpeta del paquete que estamos borrando
            foreach ($vacacion->foto as $foto) {
                if (Storage::disk('public')->exists($foto->ruta)) {
                    Storage::disk('public')->delete($foto->ruta);
                }
            }

            // Aqui lo que hacemos es borrar los registros referentes a todo este paquete vacacional
            $vacacion->foto()->delete();      
            $vacacion->comentario()->delete(); 
            $vacacion->reserva()->delete();    

            // Terminamos de borrar el paquete, una vez que hemos elimando todo lo relacionado con el 
            $result = $vacacion->delete();
            $mensajetxt = 'El paquete y todos sus datos asociados se han eliminado correctamente';
            
            // Recogemos los errores
        } catch(UniqueConstraintViolationException $e) {
            $mensajetxt = "Llave primaria";
        } catch (QueryException $e) {
            $mensajetxt = "Valor nulo";
        } catch (\Exception $e) {
            $mensajetxt = "Error fatal". $e->getMessage();
        }

        $mensaje = ['mensajeTexto' => $mensajetxt];

        // Redirección según el resultado
        if ($result) {
            return redirect()->route('vacacion.lista')->with($mensaje);
        } else {
            return back()->withErrors($mensaje);
        }
    }

    // Función que nos devuelve la lista, para que el admin vea todos los paquetes 
    function lista() {
        $vacaciones = Vacacion::all();
        return view('vacacion.lista', ['vacaciones' => $vacaciones ]);
    }
}
