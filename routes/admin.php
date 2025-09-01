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
	Route::resource('user', UserController::class);
	Route::resource('role-menu', P_rolemenu::class);
	Route::get('roles', [P_role::class, 'index'])->name('role.index');
	Route::get('roles/data', [P_role::class, 'get_data'])->name('roles.data');
    Route::get('roles/show/{id}', [P_role::class, 'show'])->name('roles.show');
});
Route::group(['prefix' => '/', 'middleware' => ['auth']], function () {
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('crm.dashboard');
});