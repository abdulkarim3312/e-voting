<?php

use UniSharp\LaravelFilemanager\Lfm;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Backend\DistrictController;



Route::get('/admin/login', [UserController::class, 'adminLogin'])->name('login');
Route::post('/admin/login/post', [UserController::class, 'adminLoginPost'])->name('admin-login-post');

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('profile-info', [AuthController::class, 'profilePage'])->name('profile_info');
    Route::post('profile-info-update', [AuthController::class, 'update'])->name('profile_update');
    Route::get('profile-password', [AuthController::class, 'passwordPage'])->name('profile_password');
    Route::post('profile-password-update', [AuthController::class, 'passwordUpdate'])->name('update_password');

    Route::resource('users', UserController::class);
    Route::post('/user-status-update/{id}', [UserController::class, 'updateStatus'])->name('user_status_update');
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    Route::post('/logout', [UserController::class, 'adminLogout'])->name('admin-logout');


    Route::controller(DistrictController::class)->group(function () {
        Route::get('/district-manage', 'districtManage')->name('district.manage');
        Route::get('/district-create', 'districtCreate')->name('district.create');
        Route::post('/district-upload', 'districtUpload')->name('district.upload');
        Route::post('/district-update', 'districtUpdate')->name('district.update');
        
        Route::get('/district-edit/{id}', 'districtEdit')->name('district.edit');
        Route::get('/district-delete/{id}', 'districtDelete')->name('district.delete');
    });


});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    Lfm::routes();
});




