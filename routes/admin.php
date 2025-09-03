<?php
// route for administrator
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\P_rolemenu;
use Modules\Crm\Http\Controllers\DashboardController;
use App\Http\Controllers\P_role;


Route::group(['prefix' => '/', 'middleware' => ['auth', 'role']], function () {
	Route::get('/', [DashboardController::class, 'index'])->name('crm.dashboard');
	// Route::get('/dashboard', 'DashboardController@index')->name('admin.module');
	Route::get('/change-password', 'DashboardController@change_password')->name('change-password');
	Route::post('/change-password', 'DashboardController@save_change_password')->name('save_change_password');
	Route::get('/list-permohonan', 'DashboardController@list_permohonan')->name('admin.module');
	Route::resource('layanan', LayananController::class);
	Route::resource('user', UserController::class)->names('user')->except('show');
	Route::resource('role-menu', P_rolemenu::class);
	Route::resource('roles', P_role::class)->names('roles')->except('show');
    Route::get('roles/detail/{id}', [P_role::class, 'show'])->name('roles.show');
    Route::post('roles/duplicate/{id}', [P_role::class, 'duplicate'])->name('roles.duplicate');
});
Route::group(['prefix' => '/', 'middleware' => ['auth']], function () {
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('crm.dashboard');
	Route::get('roles/data', [P_role::class, 'get_data'])->name('roles.data');
	Route::get('user/data', [UserController::class, 'get_data'])->name('user.data');
});
