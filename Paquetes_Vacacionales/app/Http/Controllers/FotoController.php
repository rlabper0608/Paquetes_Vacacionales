<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use Illuminate\Http\Request;

class FotoController extends Controller {
    function __construct() {
        // $this->middleware('verified')->except(['index', 'pelo', 'show']);
    }

    public function index(): View {
        
    }

    public function create(): View {
        
    }

    public function store(Request $request): RedirectResponse {
        
    }

    public function show(Vacacion $vacacion): View {
        
    }

    public function edit(Vacacion $vacacion): View {
        
    }

    public function update(Request $request, Vacacion $vacacion): RedirectResponse {
        
    }

    public function destroy(Vacacion $vacacion): RedirectResponse {
        
    }
}
