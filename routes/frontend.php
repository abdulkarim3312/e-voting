<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;



Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'home')->name('home.view');
    Route::get('/results', 'results')->name('home.results');
});
