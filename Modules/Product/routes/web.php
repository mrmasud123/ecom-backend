<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\AttributeController;
use Modules\Product\Http\Controllers\BrandController;
use Modules\Product\Http\Controllers\ProductController;
use Modules\Product\Http\Controllers\CategoryController;

Route::middleware(['auth:sanctum'])->group(function () {

    //Products Routes
    Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
    Route::resource('products', ProductController::class)->names('product');

    //Category Routes
    Route::get('/categories/data', [CategoryController::class, 'data'])->name('categories.data');
    Route::resource('categories', CategoryController::class)->names('category');

    Route::prefix('brands')->name('brands.')->group(function () {
        Route::get('/data', [BrandController::class, 'data'])->name('data');
        Route::resource('/', BrandController::class)->parameters(['' => 'brand']);
    });

    Route::prefix('attributes')->name('attributes.')->group(function () {
        Route::get('/data', [AttributeController::class, 'data'])->name('data');
        Route::resource('/', AttributeController::class)->parameters(['' => 'attribute']);
    });

});
