<?php

use Illuminate\Support\Facades\Route;
use Modules\Marketing\Http\Controllers\DiscountController;

Route::middleware(['auth:sanctum',])->group(function () {
    Route::get('/discounts/data', [DiscountController::class, 'data'])->name('discounts.data');
    Route::get('/discounts/stats', [DiscountController::class, 'stats'])->name('discounts.stats');
    Route::get('/discounts/toggle', [DiscountController::class, 'toggle'])->name('discounts.toggle');
    Route::resource('/discount', DiscountController::class)->names('discount');
});
