<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::resource('marcas', MarcaController::class);
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/admin', [HomeController::class, 'dash'])->name('admin')->middleware('auth.admin');
Route::resource('marcas', MarcaController::class)->middleware('auth.admin');
Route::resource('modelos', ModeloController::class)->middleware('auth.admin');
Route::get('/marcas-index', [MarcaController::class, 'index']);