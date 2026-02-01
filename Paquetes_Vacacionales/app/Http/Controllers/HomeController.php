<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller {

    function __construct() {
        $this->middleware('auth');
    }

    function index() {
        return view('auth.home');
    }
}
