<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsersController extends Controller {
    function __construct() {
        // $this->middleware('verified')->except(['index', 'pelo', 'show']);
    }

    public function index(): View {
        return view('user.index');
    }

    public function create(): View {
        return view('user.create');
    }

    public function store(Request $request): RedirectResponse {
        
    }

    public function show(Vacacion $vacacion): View {
        return view('user.show');
    }

    public function edit(Vacacion $vacacion): View {
        return view('user.edit');
    }

    public function update(Request $request, Vacacion $vacacion): RedirectResponse {
        
    }

    public function destroy(Vacacion $vacacion): RedirectResponse {
        
    }
}
