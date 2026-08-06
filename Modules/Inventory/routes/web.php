<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\InventoryController;
use Modules\Inventory\Http\Controllers\PurchaseOrderController;
use Modules\Inventory\Http\Controllers\StockController;

Route::middleware(['auth:sanctum'])->group(function () {
//    Route::resource('inventories', InventoryController::class)->names('inventory');
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/data', [StockController::class, 'data'])->name('data');
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::post('/adjustments', [StockController::class, 'adjust'])->name('adjustments.store');
    });

    Route::prefix('purchase-orders')->name('purchase-orders.')->group(function () {
        Route::get('/data', [PurchaseOrderController::class, 'data'])->name('data');
        Route::resource('/', PurchaseOrderController::class)->parameters(['' => 'purchase_order']);
    });
});
