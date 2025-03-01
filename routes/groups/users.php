<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\UserController::class)->group(function (){
    Route::post('login', 'login')->name('login');
});
