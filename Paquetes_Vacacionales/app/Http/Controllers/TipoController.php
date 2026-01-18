<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TipoController extends Controller {
    function __construct() {
        // $this->middleware('verified')->except(['index', 'pelo', 'show']);
    }

    public function index(): View {
        return view('tipo.index');
    }

    public function create(): View {
        return view('tipo.create');
    }

    public function store(Request $request): RedirectResponse {
        
    }

    public function show(Vacacion $vacacion): View {
        return view('tipo.show');
    }

    public function edit(Vacacion $vacacion): View {
        return view('tipo.edit');
    }

    public function update(Request $request, Vacacion $vacacion): RedirectResponse {
        
    }

    public function destroy(Vacacion $vacacion): RedirectResponse {
        
    }
}
