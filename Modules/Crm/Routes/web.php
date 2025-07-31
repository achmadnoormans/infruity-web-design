<?php
use Illuminate\Support\Facades\Route;
use Modules\Pos\Http\Controllers\PosController;
use Modules\Pos\Http\Controllers\SettingNotaController;
use Modules\Master\Http\Controllers\CustomerController;
use Modules\Crm\Http\Controllers\TierController;
use Modules\Crm\Http\Controllers\SettingExpController;
use Modules\Crm\Http\Controllers\PointScheduleController;
use Modules\Crm\Http\Controllers\DashboardController;

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
    Route::get('tier/get-gift/{id}', [TierController::class, 'getGift'])->name('tier.get-gift');
    Route::resource('setting-exp', SettingExpController::class)->names('setting-exp')->except('show');
    Route::resource('point-schedule', PointScheduleController::class)->names('point-schedule')->except('show');
    Route::get('customer-report', [TierController::class, 'customerReport'])->name('customer.report');
    Route::get('customer-report/data', [TierController::class, 'customerReportData'])->name('customer.report.data');
    Route::get('crm-dashboard', [DashboardController::class, 'index'])->name('crm.dashboard');
    Route::get('crm-dashboard/top-distribution', [DashboardController::class, 'topDistribution'])->name('crm.dashboard.top-distribution');
    Route::get('crm-dashboard/top-tier', [DashboardController::class, 'topTier'])->name('crm.dashboard.top-tier');
    Route::get('crm-dashboard/graphic-tier', [DashboardController::class, 'tierGraphic'])->name('crm.dashboard.tier-graphic');
    Route::get('crm-dashboard/gender-distribution', [DashboardController::class, 'genderDistribution'])->name('crm.dashboard.gender-distribution');
    Route::get('crm-dashboard/customer-distribution', [DashboardController::class, 'customerGraphic'])->name('crm.dashboard.customer-distribution');
});
