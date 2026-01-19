<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Vacacion;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReservaController extends Controller {

    public function index(): View {
        if (Auth::user()->rol == 'admin') {
            $reservas = Reserva::with(['user', 'vacacion.foto'])->paginate(10);
        } else {
            $reservas = Reserva::where('iduser', Auth::id())
                                ->with(['vacacion.foto'])
                                ->paginate(10);
        }
        return view('reserva.index', ['reservas' => $reservas]);
    }

    public function create(): View {
        $vacaciones = Vacacion::all();
        return view('reserva.create', ['vacaciones' => $vacaciones]);
    }

    public function store(Request $request) {
        $reserva = new Reserva($request->all());
        $reserva->iduser = auth()->id();
        $result = false;

        try {
            $result = $reserva->save();
            $mensajetxt = "Reserva realizada";
        } catch(UniqueConstraintViolationException $e) {
            $mensajetxt = "Error: Ya existe esta reserva";
        } catch (QueryException $e) {
            $mensajetxt = "Error de datos";
        } catch (\Exception $e) {
            $mensajetxt = "Error fatal";
        }

        if ($result) {
            return view('reserva.success', ['reserva'=>$reserva]);
        } else {
            return back()->withInput()->withErrors(['mensajeTexto' => $mensajetxt]);
        }
    }

    public function show(Reserva $reserva): View {
        return view('reserva.show', ['reserva' => $reserva]);
    }

    public function edit(Reserva $reserva): View {
        $vacaciones = Vacacion::all();
        return view('reserva.edit', ['reserva' => $reserva, 'vacaciones' => $vacaciones]);
    }

    public function update(Request $request, Reserva $reserva): RedirectResponse {
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

    public function destroy(Reserva $reserva): RedirectResponse {
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
}