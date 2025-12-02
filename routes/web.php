<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/home', [ProductController::class, 'indexWeb'])->name('home');

Route::get('/products', function () {
    return view('products');
})->name('products');

Route::get('/cards', function () {
    return view('cards');
})->name('cards');

Route::get('/collectibles', function () {
    return view('collectibles');
})->name('collectibles');

Route::get('/admin/products', function () {
    return view('admin.products');
})->name('admin.products');

// Ruta para ver detalle del producto
Route::get('/product/{id}', [ProductController::class, 'showWeb'])->name('product.detail');