<?php

use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::resource('marcas', MarcaController::class);
// Route::resource('modelos', ModeloController::class);
// Route::resource('productos', ProductoController::class);
// Route::resource('ordenes', OrdenController::class);
// Route::put('/productos-change', [ProductoController::class, 'changeState']);

