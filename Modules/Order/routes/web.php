<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

Route::middleware(['auth:sanctum'])->prefix('orders')->group(function () {
//    Route::resource('orders', OrderController::class)->names('order');

        Route::get('/data', [OrderController::class, 'data'])->name('data');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::get('/', [OrderController::class, 'index'])->name('index');

});
