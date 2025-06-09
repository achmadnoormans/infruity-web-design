<?php
use Illuminate\Support\Facades\Route;
use Modules\Pos\Http\Controllers\PosController;

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
    Route::get('pos/show/{id}', [PosController::class,'show'])->name('pos.show');
    Route::get('pos/data', [PosController::class, 'get_data'])->name('pos-data');
});
