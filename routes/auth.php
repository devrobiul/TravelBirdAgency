<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::controller(LoginController::class)->group(function(){
    Route::get('/','login')->name('login');
    Route::post('/login/store','loginStore')->name('login.store');
});