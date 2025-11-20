<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']); 
//ENDPOINT PARA AGREGAR/ACTUALIZAR PUNTUACIONES
Route::middleware('auth:sanctum')->post('/products/{product}/ratings', [RatingController::class, 'store']); 
//ENDPOINT PARA OBTENER EL PRODUCTO MEJOR VALORADO 
Route::get('/products/best-rated', [RatingController::class, 'bestRatedProduct']);