<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ZoneController;
use App\Http\Controllers\Backend\OfficeController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\DistrictController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\CandidateController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\DesignationController;
use App\Http\Controllers\Backend\ElectionController;



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
    Route::controller(DesignationController::class)->group(function () {
        Route::get('/designation-manage', 'designationManage')->name('designation.manage');
        Route::get('/designation-create', 'designationCreate')->name('designation.create');
        Route::post('/designation-upload', 'designationUpload')->name('designation.upload');
        Route::post('/designation-update', 'designationUpdate')->name('designation.update');
        
        Route::get('/designation-edit/{id}', 'designationEdit')->name('designation.edit');
        Route::get('/designation-delete/{id}', 'designationDelete')->name('designation.delete');
    });

    Route::controller(CandidateController::class)->group(function () {
        Route::get('/candidate-manage', 'candidateManage')->name('candidate.manage');
        Route::get('/candidate-create', 'candidateCreate')->name('candidate.create');
        Route::post('/candidate-upload', 'candidateUpload')->name('candidate.upload');
        Route::post('/candidate-update', 'candidateUpdate')->name('candidate.update');
        
        Route::get('/candidate-delete/{id}', 'candidateDelete')->name('candidate.delete');
    });


    Route::controller(ZoneController::class)->group(function () {
        Route::get('/zone-manage', 'zoneManage')->name('zone.manage');
        Route::get('/zone-create', 'zoneCreate')->name('zone.create');
        Route::post('/zone-upload', 'zoneUpload')->name('zone.upload');
        Route::post('/zone-update', 'zoneUpdate')->name('zone.update');
        
        Route::get('/zone-edit/{id}', 'zoneEdit')->name('zone.edit');
        Route::get('/zone-delete/{id}', 'zoneDelete')->name('zone.delete');
    });


    Route::controller(OfficeController::class)->group(function () {
        Route::get('/office-manage', 'officeManage')->name('office.manage');
        Route::get('/office-create', 'officeCreate')->name('office.create');
        Route::post('/office-upload', 'officeUpload')->name('office.upload');
        Route::post('/office-update', 'officeUpdate')->name('office.update');
        
        Route::get('/office-edit/{id}', 'officeEdit')->name('office.edit');
        Route::get('/office-delete/{id}', 'officeDelete')->name('office.delete');
    });

    Route::controller(EmployeeController::class)->group(function () {
        Route::get('/employee-voter', 'employeeVoterManage')->name('employee.voter');
        Route::get('/employee-manage', 'employeeManage')->name('employee.manage');
        Route::get('/employee-create', 'employeeCreate')->name('employee.create');
        Route::post('/employee-upload', 'employeeUpload')->name('employee.upload');
        Route::post('/employee-update/{id}', 'employeeUpdate')->name('employee.update');
        
        Route::get('/employee-edit/{id}', 'employeeEdit')->name('employee.edit');
        Route::get('/employee-delete/{id}', 'employeeDelete')->name('employee.delete');
    });

    Route::get('employee/voter', [EmployeeController::class,'employeeVoterManage'])->name('employee.voter.manage');

    Route::get('employee/voter/fetch', [EmployeeController::class,'employeeVoterFetch'])->name('employee.voter.fetch');
    Route::get('/employee-voter/pdf', [EmployeeController::class, 'downloadVoterPdf'])->name('employee.voter.pdf');


    Route::controller(ElectionController::class)->group(function () {
        Route::get('/election-manage', 'electionManage')->name('election.manage');
        Route::post('/election-update', 'electionUpdate')->name('election.update');
    });


});




