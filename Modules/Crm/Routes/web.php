<?php
use Illuminate\Support\Facades\Route;
use Modules\Pos\Http\Controllers\PosController;
use Modules\Pos\Http\Controllers\SettingNotaController;
use Modules\Master\Http\Controllers\CustomerController;
use Modules\Crm\Http\Controllers\TierController;
use Modules\Crm\Http\Controllers\SettingExpController;
use Modules\Crm\Http\Controllers\PointScheduleController;

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
    Route::resource('tier', TierController::class)->names('tier')->except('show');
    Route::get('tier/data', [TierController::class, 'get_data'])->name('tier.data');
    Route::post('tier/{id}/save-detail', [TierController::class, 'saveDetail'])->name('tier.save_detail');
    Route::resource('setting-exp', SettingExpController::class)->names('setting-exp')->except('show');
    Route::resource('point-schedule', PointScheduleController::class)->names('point-schedule')->except('show');
    Route::get('customer-report', [TierController::class, 'customerReport'])->name('customer.report');
    Route::get('customer-report/data', [TierController::class, 'customerReportData'])->name('customer.report.data');
});
