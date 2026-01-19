<?php

use App\Http\Controllers\ComentarioController; 
use App\Http\Controllers\FotoController; 
use App\Http\Controllers\HomeController; 
use App\Http\Controllers\MainController; 
use App\Http\Controllers\ReservaController; 
use App\Http\Controllers\UsersController; 
use App\Http\Controllers\VacacionController; 
use App\Http\Controllers\TipoController; 
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'main'])->name('main');          
Route::get('about', [MainController::class, 'about'])->name('about'); 
Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard'); 

Route::resource('comentario', ComentarioController::class);
Route::resource('reserva', ReservaController::class);
Route::resource('vacacion', VacacionController::class);
Route::resource('tipo', TipoController::class);
Route::resource('user', UsersController::class);

Route::get('vacacion/{id}/fotos', [FotoController::class, 'gestionFotos'])->name('vacacion.fotos');

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home/edit', [HomeController::class, 'edit'])->name('home.edit');
Route::put('/home', [HomeController::class, 'update'])->name('home.update');

// Auth::routes(['verify'=> true]); 
Auth::routes();