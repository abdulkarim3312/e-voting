<?php

use UniSharp\LaravelFilemanager\Lfm;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ZoneController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\DistrictController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PermissionController;



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

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category-manage', 'categoryManage')->name('category.manage');
        Route::get('/category-create', 'categoryCreate')->name('category.create');
        Route::post('/category-upload', 'categoryUpload')->name('category.upload');
        Route::post('/category-update', 'categoryUpdate')->name('category.update');
        
        Route::get('/category-edit/{id}', 'categoryEdit')->name('category.edit');
        Route::get('/category-delete/{id}', 'categoryDelete')->name('category.delete');
    });


    Route::controller(ZoneController::class)->group(function () {
        Route::get('/zone-manage', 'zoneManage')->name('zone.manage');
        Route::get('/zone-create', 'zoneCreate')->name('zone.create');
        Route::post('/zone-upload', 'zoneUpload')->name('zone.upload');
        Route::post('/zone-update', 'zoneUpdate')->name('zone.update');
        
        Route::get('/zone-edit/{id}', 'zoneEdit')->name('zone.edit');
        Route::get('/zone-delete/{id}', 'zoneDelete')->name('zone.delete');
    });


});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    Lfm::routes();
});




