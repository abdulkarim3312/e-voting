<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Userend\Auther;
use App\Http\Controllers\Userend\profileController;
use App\Http\Controllers\Userend\VotingController;

Route::prefix('user')->middleware(['GuestUser'])->group(function () {

    Route::get('/login', function () {
        return view('userend.auth.login');
    });

    Route::get('/registration', function () {
        return view('userend.auth.registration');
    });

    Route::controller(Auther::class)->group(function () {
        
        Route::post('/registration/process', 'userCreate')->name('user.create');

        
        Route::post('/login/process', 'userLogin')->name('user.login');

    });

});



Route::prefix('user')->middleware(['user'])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('userend.dashboard.view');
    });

    Route::controller(Auther::class)->group(function () {
        Route::get('/logout', 'userLogout')->name('user.logout');
    });

    Route::get('/voting', [VotingController::class, 'candidateList'])->name('user.voting');
    Route::post('/vote', [VotingController::class, 'store'])->name('vote.store');
    Route::get('/results/{category}', [VotingController::class, 'results'])->name('vote.results');


    Route::controller(profileController::class)->group(function () {
        Route::get('/profile', 'userProfile')->name('user.profile');
        Route::post('/profile/update', 'userUpdate')->name('user.profile.update');
    });



});
