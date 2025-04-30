<?php

use Modules\Master\Http\Controllers\ProductController;
use Modules\Master\Http\Controllers\ProductCategoryController;
use Modules\Master\Http\Controllers\ProductUnitController;
use Modules\Master\Http\Controllers\LocationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('/')->group(function () {
    // Route::get('/products', function () {
    //     return view('Master::products.index'); // di view ini kamu panggil @livewire('product-table')
    // });
    Route::resource('products', ProductController::class)->names('products')->except('show');
    Route::get('products/data', [ProductController::class, 'get_data'])->name('products-data');

    Route::resource('category', ProductCategoryController::class)->names('category')->except('show');
    Route::get('category/data', [ProductCategoryController::class, 'get_data'])->name('category-data');
    Route::get('category/export', [ProductCategoryController::class, 'excel'])->name('export-data');

    Route::resource('unit', ProductUnitController::class)->names('unit')->except('show');
    Route::get('unit/data', [ProductUnitController::class, 'get_data'])->name('unit-data');

    Route::resource('location', LocationController::class)->names('location')->except('show');
    Route::get('location/data', [LocationController::class, 'get_data'])->name('location-data');
});
