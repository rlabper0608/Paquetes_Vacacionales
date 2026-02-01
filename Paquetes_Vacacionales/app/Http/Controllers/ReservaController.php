<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservaCreateRequest;
use App\Models\Reserva;
use App\Models\Vacacion;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReservaController extends Controller {

    function __construct() {
        // Solo el admin puede pasar a ver reservas, que es el listado de todas las reservas que se han realizado en la página
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->rol === 'admin') {
                // Nos deja pasar si soy el admin
                return $next($request);
            }

            // Si no es admin, lo mandamos a la home con un error
            return redirect('/')->withErrors(['mensajeTexto' => 'Acceso denegado. Solo administradores.']);
        })->only(['reservas']);
    }

    // Ver listado de reservas
    function index(): View {
        $reservas = Reserva::where('iduser', Auth::id())->paginate(10);
        return view('reserva.index', ['reservas' => $reservas]);
    }

    // Enviar a la creación de una reserva (no es necesario)
    function create(): View {
        $vacaciones = Vacacion::all();
        return view('reserva.create', ['vacaciones' => $vacaciones]);
    }

    // Guardar una reserva en la base de datos
    function store(ReservaCreateRequest $request) {
        $reserva = new Reserva($request->all());
        $reserva->iduser = auth()->id();
        
        $result = false;

        try {
            $result = $reserva->save();
            $mensajetxt = "¡Reserva realizada con éxito!";
        } catch(UniqueConstraintViolationException $e) {
            $mensajetxt = "Error: Ya tienes una reserva para este destino en esa fecha.";
        } catch (QueryException $e) {
            $mensajetxt = "Error en la base de datos. Comprueba los campos.";
        } catch (\Exception $e) {
            $mensajetxt = "Ocurrió un error inesperado al procesar tu reserva.";
        }

        if ($result) {
            return view('reserva.success', ['reserva' => $reserva]);
        } else {
            return back()->withInput()->withErrors(['mensajeTexto' => $mensajetxt]);
        }
    }

    // Ver una resreva de manera individual
    function show(Reserva $reserva): View {
        return view('reserva.show', ['reserva' => $reserva]);
    }

    // Mandar a la edición de una reserva (no es necesario)
    function edit(Reserva $reserva): View {
        $vacaciones = Vacacion::all();
        return view('reserva.edit', ['reserva' => $reserva, 'vacaciones' => $vacaciones]);
    }

    // Guardar en la base de datos los cambios de una reserva (no es necesario)
    function update(Request $request, Reserva $reserva): RedirectResponse {
        $result = false;
        $reserva->fill($request->all());

        try {
            $result = $reserva->save();
            $mensajetxt = "La reserva se ha modificado correctamente.";
        } catch(\Exception $e) {
            $result = false;
            $mensajetxt = "Error al actualizar";
        }

        if ($result) {
            return redirect()->route('main')->with('mensajeTexto', $mensajetxt);
        } else {
            return back()->withInput()->withErrors(['mensajeTexto' => $mensajetxt]);
        }
    }

    // Borrado de una reserva
    function destroy(Reserva $reserva): RedirectResponse {
        try {
            $result = $reserva->delete();
            $mensajetxt = 'La reserva ha sido cancelada correctamente';
        } catch(\Exception $e) {
            $result = false;
            $mensajetxt = 'No se pudo cancelar';
        }

        if($result) {
            return redirect()->route('main')->with('mensajeTexto', $mensajetxt);
        } else {
            return back()->withErrors(['mensajeTexto' => $mensajetxt]);
        }
    }

    // Devuelve la lista de todas las reservas que se han realizado en la página
    function reservas(): View {
        $reservas = Reserva::all();
        return view ('reserva.reservas', ['reservas' => $reservas]);
    }
}