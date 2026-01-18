<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservaController extends Controller {
    function __construct() {
        // $this->middleware('verified')->except(['index', 'pelo', 'show']);
    }

    public function index(): View {
        return view('reserva.index');
    }

    public function create(): View {
        return view('reserva.create');
    }

    public function store(Request $request): RedirectResponse {
        
    }

    public function show(Vacacion $vacacion): View {
        return view('create.show');
    }

    public function edit(Vacacion $vacacion): View {
        return view('create.edit');
    }

    public function update(Request $request, Vacacion $vacacion): RedirectResponse {
        
    }

    public function destroy(Vacacion $vacacion): RedirectResponse {
        
    }
}
