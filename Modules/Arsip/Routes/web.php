<?php
use Illuminate\Support\Facades\Route;
use Modules\Arsip\Http\Controllers\ArsipController;
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

Route::prefix('arsip')->group(function() {
    Route::get('/', 'ArsipController@index');
});

Route::group(['prefix' => '/', 'middleware' => ['auth', 'cek-arsip']], function () {
    Route::resource('arsip', ArsipController::class)->names('arsip')->except('show');
    Route::get('arsip/{id}/detail', [ArsipController::class, 'show']);
    Route::get('arsip/get-data', [ArsipController::class, 'get_data'])->name('arsip.get-data');
    Route::get('arsip-2025', [ArsipController::class, 'index'])->name('arsip.index');
    Route::get('arsip-2024', [ArsipController::class, 'index'])->name('arsip.index');
});
