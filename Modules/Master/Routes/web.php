<?php

use Modules\Master\Http\Controllers\ProductController;
use Modules\Master\Http\Controllers\ProductCategoryController;
use Modules\Master\Http\Controllers\ProductUnitController;
use Modules\Master\Http\Controllers\LocationController;
use Modules\Master\Http\Controllers\HandlingController;
use Modules\Master\Http\Controllers\DepartmentController;
use Modules\Master\Http\Controllers\PositionController;
use Modules\Master\Http\Controllers\SupplierController;
use Modules\Master\Http\Controllers\CustomerController;
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

Route::group(['prefix' => '/', 'middleware' => ['auth']], function () {
    // Route::get('/products', function () {
    //     return view('Master::products.index'); // di view ini kamu panggil @livewire('product-table')
    // });
    Route::resource('products', ProductController::class)->names('products')->except('show');
    Route::get('products/data', [ProductController::class, 'get_data'])->name('products-data');
    Route::put('products/{id}/update-price', [ProductController::class, 'updatePrice']);

    Route::resource('category', ProductCategoryController::class)->names('category')->except('show');
    Route::get('category/data', [ProductCategoryController::class, 'get_data'])->name('category-data');
    Route::get('category/export', [ProductCategoryController::class, 'excel'])->name('export-data');

    Route::resource('unit', ProductUnitController::class)->names('unit')->except('show');
    Route::get('unit/data', [ProductUnitController::class, 'get_data'])->name('unit-data');

    Route::resource('location', LocationController::class)->names('location')->except('show');
    Route::get('location/data', [LocationController::class, 'get_data'])->name('location-data');

    Route::resource('handling', HandlingController::class)->names('handling')->except('show');
    Route::get('handling/data', [HandlingController::class, 'get_data'])->name('handling-data');

    Route::resource('department', DepartmentController::class)->names('department')->except('show');
    Route::get('department/data', [DepartmentController::class, 'get_data'])->name('department-data');
    Route::get('department/export', [DepartmentController::class, 'excel'])->name('export-department-data');

    Route::resource('position', PositionController::class)->names('position')->except('show');
    Route::get('position/data', [PositionController::class, 'get_data'])->name('position-data');
    Route::get('position/export', [PositionController::class, 'excel'])->name('export-position-data');

    Route::resource('supplier', SupplierController::class)->names('supplier')->except('show');
    Route::get('supplier/data', [SupplierController::class, 'get_data'])->name('supplier-data');
    // Route::get('supplier/export', [SupplierController::class, 'excel'])->name('supplier-data');

    Route::resource('customers', CustomerController::class)->names('customers')->except('show');
    Route::get('customers/data', [CustomerController::class, 'get_data'])->name('customers-data');
});
