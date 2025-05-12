<?php
use Modules\Transaction\Http\Controllers\WholesaleController;
use Modules\Transaction\Http\Controllers\SortirController;
use Modules\Transaction\Http\Controllers\ProductReceiptController;
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
    Route::resource('wholesale', WholesaleController::class)->names('wholesale')->except('show');    
    Route::get('wholesale/data', [WholesaleController::class, 'get_data'])->name('wholesale-data');
    Route::get('wholesale/receive-product/{id}', [WholesaleController::class, 'receive_product'])->name('wholesale.receive_product');    
    Route::post('wholesale/save-receive', [WholesaleController::class, 'save_receive'])->name('wholesale-save-receive');
    Route::get('wholesale/show/{id}', [WholesaleController::class,'show'])->name('wholesale.show');
    Route::post('wholesale/receive/{id}', [WholesaleController::class,'receive_product'])->name('wholesale.receive_product');
    Route::get('wholesale/get-product/{id}', [WholesaleController::class,'get_product'])->name('wholesale.get-product');
    Route::get('wholesale/edit-product/{id}', [WholesaleController::class,'edit_product'])->name('wholesale.edit-product');
    Route::put('wholesale/update-product/{id}', [WholesaleController::class,'edit_product'])->name('wholesale.edit-product');
    Route::post('wholesale/save-product', [WholesaleController::class,'save_product'])->name('wholesale.save-product');
    Route::delete('wholesale/delete-product/{id}', [WholesaleController::class,'delete_product'])->name('wholesale.delete_product');

    Route::get('sortir', [SortirController::class, 'index'])->name('sortir');
    Route::get('sortir/data', [SortirController::class, 'get_data'])->name('sortir-data');
    Route::get('sortir/show/{id}', [SortirController::class, 'show'])->name('sortir.show');
    Route::post('sortir/save-stock', [SortirController::class, 'save_stock'])->name('sortir.save-stock');

    Route::resource('product-receipt', ProductReceiptController::class)->names('product-receipt')->except('show');  
});
