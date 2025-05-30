<?php
use Illuminate\Support\Facades\Route;
use Modules\Transaction\Http\Controllers\WholesaleController;
use Modules\Transaction\Http\Controllers\SortirController;
use Modules\Transaction\Http\Controllers\ProductReceiptController;
use Modules\Master\Http\Controllers\ProductController;
use Modules\Transaction\Http\Controllers\StockOutController;
use Modules\Transaction\Http\Controllers\StockOpnameController;
use Modules\Transaction\Http\Controllers\StockOutTypeController;
use Modules\Transaction\Http\Controllers\ProductionController;
use Modules\Transaction\Http\Controllers\ProductionParcelController;
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
    Route::get('product-stock', [ProductController::class, 'get_stock'])->name('product-stock');
    Route::get('product-stock/{id}/show', [ProductController::class, 'show_stock'])->name('product-stock.show');
    Route::get('product-stock/data', [ProductController::class, 'get_data_stock'])->name('product-stock-data');
    Route::get('product-stock/available-stock', [ProductController::class, 'get_data_available'])->name('ajax.stock-available');
    Route::get('product-stock-data-show', [ProductController::class, 'get_data_stock_show'])->name('product-stock-data-show');

    Route::resource('wholesale', WholesaleController::class)->names('wholesale')->except('show');    
    Route::get('wholesale/data', [WholesaleController::class, 'get_data'])->name('wholesale-data');
    Route::get('wholesale/{id}/show', [WholesaleController::class, 'show'])->name('wholesale-show');
    Route::get('wholesale/receive-product/{id}', [WholesaleController::class, 'receive_product'])->name('wholesale.receive_product');    
    Route::post('wholesale/save-receive', [WholesaleController::class, 'save_receive'])->name('wholesale-save-receive');
    Route::get('wholesale/show/{id}', [WholesaleController::class,'show'])->name('wholesale.show');
    Route::post('wholesale/receive/{id}', [WholesaleController::class,'receive_product'])->name('wholesale.receive_product');
    Route::get('wholesale/process/{id}', [WholesaleController::class,'receive_process'])->name('wholesale.receive_process');
    Route::get('wholesale/get-product/{id}', [WholesaleController::class,'get_product'])->name('wholesale.get-product');
    Route::get('wholesale/edit-product/{id}', [WholesaleController::class,'edit_product'])->name('wholesale.edit-product');
    Route::put('wholesale/update-product/{id}', [WholesaleController::class,'update_product'])->name('wholesale.update-product');
    Route::post('wholesale/save-product', [WholesaleController::class,'save_product'])->name('wholesale.save-product');
    Route::delete('wholesale/delete-product/{id}', [WholesaleController::class,'delete_product'])->name('wholesale.delete_product');
    Route::post('wholesale/update-receive-product/{id}', [WholesaleController::class,'update_receive_product'])->name('wholesale.update_receive_product');
    Route::post('wholesale/set-selesai/{id}', [WholesaleController::class,'set_selesai'])->name('wholesale.set_selesai');
    Route::get('wholesale/table-product-data', [WholesaleController::class, 'getProductTableData'])->name('wholsale.product-table-data');

    Route::get('sortir', [SortirController::class, 'index'])->name('sortir');
    Route::get('sortir/data', [SortirController::class, 'get_data'])->name('sortir-data');
    Route::get('sortir/show/{id}', [SortirController::class, 'show'])->name('sortir.show');
    Route::post('sortir/save-stock', [SortirController::class, 'save_stock'])->name('sortir.save-stock');

    Route::resource('product-receipt', ProductReceiptController::class)->names('product-receipt')->except('show');
    
    Route::resource('stock-out', StockOutController::class)->names('stock-out')->except('show');
    Route::get('stock-out/data', [StockOutController::class, 'get_data'])->name('stock-out.data');
    
    Route::resource('stock-opname', StockOpnameController::class)->names('stock-opname')->except('show');
    Route::get('stock-opname/data', [StockOpnameController::class, 'get_data'])->name('stock-opname.data');

    Route::resource('stock-out-type', StockOutTypeController::class)->names('stock-out-type')->except('show');
    Route::get('stock-out-type/data', [StockOutTypeController::class, 'get_data'])->name('stock-out-type.data');
    
    Route::resource('production', ProductionController::class)->names('production')->except('show');
    Route::get('production/data', [ProductionController::class, 'get_data'])->name('production-data');
    Route::get('products/get-receipt', [ProductReceiptController::class, 'getReceipt'])->name('products.get-receipt');

    Route::resource('receipt', ProductReceiptController::class)->names('receipt')->except('show');
    Route::get('receipt/data', [ProductReceiptController::class, 'get_data'])->name('receipt-data');

    Route::resource('parcel', ProductionParcelController::class)->names('parcel')->except('show');
    Route::get('parcel/show/{id}', [ProductionParcelController::class, 'show'])->name('parcel.show');
    Route::get('parcel/data', [ProductionParcelController::class, 'get_data'])->name('parcel-data');
    Route::get('parcel/process/{id}', [ProductionParcelController::class, 'process'])->name('parcel.process');
    Route::get('parcel/get-product/{id}', [ProductionParcelController::class,'get_product'])->name('parcel.get-product');
    Route::get('parcel/edit-product/{id}', [ProductionParcelController::class,'edit_product'])->name('parcel.edit-product');
    Route::put('parcel/update-product/{id}', [ProductionParcelController::class,'update_product'])->name('parcel.update-product');
    Route::post('parcel/save-product', [ProductionParcelController::class,'save_product'])->name('parcel.save-product');
    Route::delete('parcel/delete-product/{id}', [ProductionParcelController::class,'delete_product'])->name('parcel.delete_product');
    Route::post('parcel/set-selesai/{id}', [ProductionParcelController::class,'set_selesai'])->name('parcel.set_selesai');

});
