<?php

use App\Http\Controllers\MasterData\CustomerController;
use App\Http\Controllers\MasterData\ItemCategoryController;
use App\Http\Controllers\MasterData\ItemController;
use App\Http\Controllers\MasterData\SupplierController;
use App\Http\Controllers\MasterData\UnitOfMeasureController;
use App\Http\Controllers\MasterData\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::inertia('orders', 'Orders')->name('orders');

    Route::middleware('permission:manage-master-data')->group(function () {
        Route::resource('items', ItemController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        Route::resource('item-categories', ItemCategoryController::class)
            ->parameters(['item-categories' => 'item_category'])
            ->only(['index', 'store', 'update', 'destroy']);

        Route::resource('units-of-measure', UnitOfMeasureController::class)
            ->parameters(['units-of-measure' => 'units_of_measure'])
            ->only(['index', 'store', 'update', 'destroy']);

        Route::resource('suppliers', SupplierController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        Route::resource('customers', CustomerController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        Route::resource('warehouses', WarehouseController::class)
            ->only(['index', 'store', 'update', 'destroy']);
    });
});

require __DIR__.'/settings.php';
