<?php


use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//ENDPOINT PARA OBTENER LOS 3 PRODUCTO MEJOR VALORADO 
Route::get('/products/top-3-rated', [RatingController::class, 'topRatedProducts']);

//ENDPOINT DE CRUD
// Rutas de productos (protegidas por autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

// index de ratings
Route::get('/ratings', [RatingController::class, 'index']);
 Route::middleware('auth:sanctum')->group(function() {
//ENDPOINT PARA AGREGAR PUNTUACIONES
Route::post('/ratings', [RatingController::class, 'store']); 
//ENPOINT PARA ACTUALIZAR PUNTUACIONES
Route::put('/ratings/{id}',  [RatingController::class, 'update']);
//ENPOINT PARA ELIMINAR UNA VALORACION
Route::delete('/ratings/{id}', [RatingController::class, 'destroy']);
//restaurar una valoracion
Route::put('/ratings/{id}/restore', [RatingController::class, 'restore']);
 });


// endpoint para registrar usuarios
//localhost:8000/api/register
Route::post('/register', [AuthController::class, 'register']);
// endpoint para login de usuarios
//localhost:8000/api/login
Route::post('/login', [AuthController::class, 'login']);
// end point para logout de usuarios
//localhost:8000/api/logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// endpoint para comentarios
// localhost:8000/api/comments
// crear comentarios

Route::middleware('auth:sanctum')->group(function () {

Route::post('/comments', [CommentController::class, 'store']);
// obtener comentarios
Route::get('/comments', [CommentController::class, 'index']);
// actualizar comentarios
Route::put('/comments/{id}', [CommentController::class, 'update']);
// eliminar comentarios
Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
    
    // Nueva ruta para verificar si el usuario ya comentó
    Route::get('/comments/check-user-comment/{productId}', [CommentController::class, 'checkUserComment']);
});
