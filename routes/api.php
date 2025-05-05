<?php

use App\Http\Controllers\API\AlbumController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\PhotoController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SaleController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Productos
    Route::apiResource('products', ProductController::class);
    
    // Categorías de productos
    Route::apiResource('product-categories', ProductCategoryController::class);
    
    // Clientes
    Route::apiResource('clients', ClientController::class);
    
    // Ventas
    Route::apiResource('sales', SaleController::class);
    
    // Álbumes
    Route::apiResource('albums', AlbumController::class);
    
    // Fotos
    Route::apiResource('photos', PhotoController::class);
    Route::post('/albums/{album}/photos', [PhotoController::class, 'uploadToAlbum']);
});