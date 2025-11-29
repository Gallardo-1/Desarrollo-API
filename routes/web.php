<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\ProductController;

Route::get('/', [WebController::class, 'login'])->name('login');
Route::get('/register', [WebController::class, 'register'])->name('register');
Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/products', function () {
    return view('products');
})->name('products');

Route::get('/cards', function () {
    return view('cards');
})->name('cards');

Route::get('/collectibles', function () {
    return view('collectibles');
})->name('collectibles');

Route::get('/product/{product}', [ProductController::class, 'showDetail'])
    ->name('product.detail');