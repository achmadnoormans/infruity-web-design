<?php
use Illuminate\Support\Facades\Route;
use Modules\Pos\Http\Controllers\PosController;
use Modules\Pos\Http\Controllers\SettingNotaController;
use Modules\Pos\Http\Controllers\DeliveryOrderController;
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
    Route::resource('pos', PosController::class)->names('pos')->except('show');
    Route::get('pos/show/{id}', [PosController::class, 'show'])->name('pos.show');
    Route::post('pos/submitTransaction', [PosController::class, 'store'])->name('pos-submit');
    Route::post('pos/{id}/payment', [PosController::class, 'savePayment'])->name('receipt.payment');
    Route::get('pos/{id}/receipt', [PosController::class, 'showReceipt'])->name('pos.receipt');
    Route::post('pos/save-transaction', [PosController::class, 'saveTransaction'])->name('pos.receipt');
    Route::post('/pos/customers', [CustomerController::class, 'storeCustomer']);
    Route::get('pos/payment/{id}', [PosController::class, 'payment'])->name('pos.payment');
    Route::post('pos/savePayment', [PosController::class, 'savePayment'])->name('pos.savePayment');
    Route::get('pos/payment-notification/{id}', [PosController::class, 'paymentNotification'])->name('pos.paymentNotification');
    Route::get('pos/listPayment/{id}', [PosController::class, 'listPayment'])->name('pos.listPayment');
    Route::get('pos/printPayment/{id}', [PosController::class, 'printPayment'])->name('pos.printPayment');
    Route::get('pos/printDraftPayment/{id}', [PosController::class, 'printDraftPayment'])->name('pos.printDraftPayment');
    Route::get('pos/printNota/{id}', [PosController::class, 'printNota'])->name('pos.printNota');
    Route::post('/upload-receipt', [PosController::class, 'uploadReceipt']);
    Route::resource('setting-nota', SettingNotaController::class)->names('setting-nota')->except('show');
    Route::get('setting-nota/view-receipt', [SettingNotaController::class, 'viewReceipt'])->name('setting-nota.view-receipt');
    Route::get('delivery-order', [DeliveryOrderController::class, 'index'])->name('delivery-order');
});
Route::group(['prefix' => '/', 'middleware' => ['auth']], function () {
    Route::get('pos/data', [PosController::class, 'get_data'])->name('pos-data');
    Route::get('delivery-order/data', [DeliveryOrderController::class, 'get_data'])->name('delivery-order.data');
});

Route::get('cek-nota/{id}', [PosController::class, 'cekNota'])->name('pos.cek-nota');
Route::get('cek-nota/draft/{id}', [PosController::class, 'cekNotaDraft'])->name('pos.cek-nota.draft');