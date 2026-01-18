<?php

namespace App\Http\Controllers;

use App\Http\Requests\VacacionCreateRequest;
use App\Models\Vacacion;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VacacionController extends Controller {

    function __construct() {
        // $this->middleware('verified')->except(['index', 'pelo', 'show']);
    }

    public function index(): View {
        return view('vacacion.index');
    }

    public function create(): View {
        return view('vacacion.create');
    }

    public function store(VacacionCreateRequest $request): RedirectResponse {
        
    }

    public function show(Vacacion $vacacion): View {
        return view('vacacion.show');
    }

    public function edit(Vacacion $vacacion): View {
        return view('vacacion.edit');
    }

    public function update(Request $request, Vacacion $vacacion): RedirectResponse {
        
    }

    public function destroy(Vacacion $vacacion): RedirectResponse {
        
    }
}
