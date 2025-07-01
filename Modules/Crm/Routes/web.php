<?php
use Illuminate\Support\Facades\Route;
use Modules\Pos\Http\Controllers\PosController;
use Modules\Pos\Http\Controllers\SettingNotaController;
use Modules\Master\Http\Controllers\CustomerController;
use Modules\Crm\Http\Controllers\TierController;

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
});
