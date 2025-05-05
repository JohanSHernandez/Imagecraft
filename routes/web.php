<?php

use Illuminate\Support\Facades\Route;

// Ruta para servir la aplicación React
Route::get('/{path?}', function () {
    return view('app');
})->where('path', '.*');