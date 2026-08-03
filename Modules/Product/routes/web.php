<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;
use Modules\Product\Http\Controllers\CategoryController;

Route::middleware(['auth:sanctum'])->group(function () {

    //Products Routes
    Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
    Route::resource('products', ProductController::class)->names('product');

    //Category Routes
    Route::get('/categories/data', [CategoryController::class, 'data'])->name('categories.data');
    Route::resource('categories', CategoryController::class)->names('category');

});
