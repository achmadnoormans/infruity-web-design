<?php
// route for administrator
use App\Http\Controllers\LayananController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\P_rolemenu;

Route::group(['prefix' => '/', 'middleware' => ['auth','role']], function () {
	Route::get('/dashboard', 'DashboardController@index')->name('admin.module');
	Route::get('/change-password', 'DashboardController@change_password')->name('change-password');
	Route::post('/change-password', 'DashboardController@save_change_password')->name('save_change_password');
	Route::get('/list-permohonan', 'DashboardController@list_permohonan')->name('admin.module');
	Route::resource('layanan', LayananController::class);
	Route::resource('user', UserController::class);
	Route::resource('role-menu', P_rolemenu::class);
});
