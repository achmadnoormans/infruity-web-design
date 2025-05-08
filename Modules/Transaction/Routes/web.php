<?php
use Modules\Transaction\Http\Controllers\WholesaleController;
use Modules\Transaction\Http\Controllers\SortirController;
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

    Route::get('sortir', [SortirController::class, 'index'])->name('sortir');
    Route::get('sortir/data', [SortirController::class, 'get_data'])->name('sortir-data');
    Route::get('sortir/show/{id}', [SortirController::class, 'show'])->name('sortir.show');
});
