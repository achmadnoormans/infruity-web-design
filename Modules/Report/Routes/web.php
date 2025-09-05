<?php

use Illuminate\Support\Facades\Route;
use Modules\Crm\Http\Controllers\CampaignController;
use Modules\Report\Http\Controllers\ReportController;

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

Route::group(['prefix' => '/', 'middleware' => ['auth', 'role']], function () {
    Route::get('report-transaction', [ReportController::class, 'index'])->name('report-transaction');
    Route::get('report-customer-transaction', [ReportController::class, 'customer_transaction'])->name('report-customer-transaction');
});

Route::group(['prefix' => '/', 'middleware' => ['auth']], function () {
    Route::get('report-transaction/data', [ReportController::class, 'get_data_transaction'])->name('report-transaction.data');
    Route::get('report-customer-transaction/data', [ReportController::class, 'get_data_customer_transaction'])->name('report-customer-transaction.data');
});
