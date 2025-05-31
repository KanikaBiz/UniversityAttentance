<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // redirector to the products index page
    return redirect()->route('products.index');
})->name('home');

Route::resource('/products', \App\Http\Controllers\ProductController::class);
Route::resource('categories', \App\Http\Controllers\CategoryController::class);
