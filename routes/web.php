<?php
// route for admin
use Illuminate\Support\Facades\Route;
use Modules\Master\Http\Controllers\ProductController;
include 'admin.php';

// Route::get('/', 'DashboardController@landing')->name('landing');
// Route::get('/', [ProductController::class, 'index'])->name('products-data');
Route::get('/', 'Auth\LoginController@showLogin')->name('login');
Route::get('/forgot-password', 'DashboardController@forgot_password')->name('forgot-password');
Route::post('/forgot-password', 'DashboardController@forgot_password_check')->name('forgot-password');
Route::put('/forgot-password', 'DashboardController@forgot_password_save')->name('forgot-password');
Route::get('/register', 'Auth\RegisterController@showRegister')->name('register');
Route::post('/register', 'Auth\RegisterController@create');
Route::get('/qrcode', 'DashboardController@generateQrCode');
// Route::get('/produk', 'DashboardController@produk')->name('produk');
Route::group(['prefix' => '/auth'], function () {
    // Route::get('/', 'Auth\LoginController@showLogin')->name('login');
    Route::get('/login', 'Auth\LoginController@showLogin')->name('login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('/logout/{id?}', 'UserController@logout');
});
