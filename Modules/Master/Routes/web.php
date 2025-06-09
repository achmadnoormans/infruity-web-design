<?php
use Illuminate\Support\Facades\Route;
use Modules\Master\Http\Controllers\ProductController;
use Modules\Master\Http\Controllers\ProductCategoryController;
use Modules\Master\Http\Controllers\ProductUnitController;
use Modules\Master\Http\Controllers\LocationController;
use Modules\Master\Http\Controllers\HandlingController;
use Modules\Master\Http\Controllers\DepartmentController;
use Modules\Master\Http\Controllers\PositionController;
use Modules\Master\Http\Controllers\SupplierController;
use Modules\Master\Http\Controllers\CustomerController;
use Modules\Master\Http\Controllers\RegionController;
use Modules\Master\Http\Controllers\StaffController;
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
    Route::get('products/{id}/show', [ProductController::class, 'show'])->name('products.show');

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
    Route::get('customers/{id}/show', [CustomerController::class, 'show'])->name('customers.show');

    Route::resource('staff', StaffController::class)->names('staff')->except('show');
    Route::get('staff/data', [StaffController::class, 'get_data'])->name('staff-data');
    Route::get('staff/show/{id}', [StaffController::class,'show'])->name('staff.show');
});

Route::group(['prefix' => '/', 'middleware' => ['auth']], function () {
    Route::get('/ajax/province', [RegionController::class, 'getProvince'])->name('ajax.province');
    Route::get('/ajax/city', [RegionController::class, 'getCity'])->name('ajax.city');
    Route::get('/ajax/district', [RegionController::class, 'getDistrict'])->name('ajax.district');
    Route::get('/ajax/village', [RegionController::class, 'getVillage'])->name('ajax.village');
    Route::get('/ajax/department', [DepartmentController::class, 'getDepartment'])->name('ajax.department');
    Route::get('/ajax/position', [PositionController::class, 'getPosition'])->name('ajax.position');
    Route::get('/ajax/category', [ProductCategoryController::class, 'getCategory'])->name('ajax.category');
    Route::get('/ajax/getVariant', [ProductController::class, 'getVariant'])->name('ajax.getVariant');
    Route::get('/ajax/getProduct', [ProductController::class, 'getProduct'])->name('ajax.getProduct');
    Route::get('/ajax/listProduct', [ProductController::class, 'listProduct'])->name('ajax.listProduct');
    Route::post('products/variant/store', [ProductController::class, 'storeVariant'])->name('products.store-variant');
    Route::get('products/variants/get', [ProductController::class, 'getVariant'])->name('variants.get');
    Route::put('products/variants/{id}', [ProductController::class, 'updateVariant'])->name('products.update-variant');
    Route::delete('products/variants/{id}', [ProductController::class, 'destroyVariant'])->name('products.destroy-variant');
    Route::get('products/get-product-receipt', [ProductController::class, 'getProductReceipt'])->name('products.get-product-receipt');
    Route::get('staff/get-staff', [StaffController::class, 'getStaff'])->name('staff.get-staff');
    Route::get('customer/get-customer', [CustomerController::class, 'getCustomer'])->name('customer.get-customer');
});
