<?php

use App\Http\Controllers\ComentarioController; 
use App\Http\Controllers\FotoController; 
use App\Http\Controllers\MainController; 
use App\Http\Controllers\ReservaController; 
use App\Http\Controllers\UserController; 
use App\Http\Controllers\VacacionController; 
use App\Http\Controllers\TipoController; 
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'main'])->name('main');          
Route::get('about', [MainController::class, 'about'])->name('about'); 
Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard'); 

Route::resource('foto', FotoController::class);
Route::resource('reserva', ReservaController::class);
Route::resource('vacacion', VacacionController::class);
Route::resource('tipo', TipoController::class);


// Auth::routes(['verify'=> true]);
Auth::routes();