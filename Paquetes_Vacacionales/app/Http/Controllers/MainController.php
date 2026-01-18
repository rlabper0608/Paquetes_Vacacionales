<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller {
    function main(): View {
        return view('main.main');
    }

    function about(): View {
        return view('main.about');
    }
}
