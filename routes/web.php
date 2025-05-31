<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::resource('categories', \App\Http\Controllers\CategoryController::class)->except(['show']);
