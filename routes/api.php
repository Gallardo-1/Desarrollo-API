<?php

use App\Http\Controllers\ComentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;
use Dom\Comment;

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

// endpoint para registrar usuarios
//localhost:8000/api/register
Route::post('/register', [UserController::class, 'register']);
// endpoint para login de usuarios
//localhost:8000/api/login
Route::post('/login', [UserController::class, 'login']);
// end point para logout de usuarios
//localhost:8000/api/logout
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

// endpoint para comentarios
// localhost:8000/api/comments
// crear comentarios
Route::post('/comments', [CommentController::class, 'store'])->middleware('auth:sanctum');
// obtener comentarios
Route::get('/comments', [CommentController::class, 'index'])->middleware('auth:sanctum');
// actualizar comentarios
Route::put('/commets/{id}', [CommentController::class, 'update'])->middleware('auth:sanctum');
// eliminar comentarios
Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->middleware('auth:sanctum');