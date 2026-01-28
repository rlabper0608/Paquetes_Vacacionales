<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller {
    
    // Devuelve la vista main
    function main(): View {
        return view('main.main');
    }

    // Devuelve la vista about
    function about(): View {
        return view('main.about');
    }
}
