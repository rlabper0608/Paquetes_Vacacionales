<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FotoController extends Controller {
    function __construct() {
        // $this->middleware('verified')->except(['index', 'pelo', 'show']);
    }

    public function index(): View {
        return view('foto.index');
    }

    public function create(): View {
        return view('foto.create');
    }

    public function store(Request $request): RedirectResponse {
        
    }

    public function show(Vacacion $vacacion): View {
        return view('foto.show');
    }

    public function edit(Vacacion $vacacion): View {
        return view('foto.show');
    }

    public function update(Request $request, Vacacion $vacacion): RedirectResponse {
        
    }

    public function destroy(Vacacion $vacacion): RedirectResponse {
        
    }
}
