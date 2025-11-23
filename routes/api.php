<?php


use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//ENDPOINT PARA OBTENER EL PRODUCTO MEJOR VALORADO 
Route::get('/products/best-rated', [RatingController::class, 'bestRatedProduct']);

//ENDPOINT DE CRUD
Route::get('/products', [ProductController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

 Route::get('/products/{product}', [ProductController::class, 'show']);

 //ENDPOINT PARA OBTENER LAS ESTADÍSTICAS DE VALORACION DE UN PRODUCTO ESPECIFICO
Route::get('/products/{product}/ratings/stats', [RatingController::class, 'showProductRating']);
 Route::middleware('auth:sanctum')->group(function() {
//ENDPOINT PARA AGREGAR PUNTUACIONES
Route::post('/products/{product}/ratings', [RatingController::class, 'store']); 
//ENPOINT PARA ACTUALIZAR PUNTUACIONES
Route::put('/products/{product}/ratings',  [RatingController::class, 'update']);
//ENPOINT PARA ELIMINAR UNA VALORACION
Route::delete('/products/{product}/ratings', [RatingController::class, 'destroy']);
 });


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

Route::middleware('auth:sanctum')->group(function () {

Route::post('/comments', [CommentController::class, 'store']);
// obtener comentarios
Route::get('/comments', [CommentController::class, 'index']);
// actualizar comentarios
Route::put('/comments/{id}', [CommentController::class, 'update']);
// eliminar comentarios
Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});
