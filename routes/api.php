<?php

use App\Http\Controllers\ProductResourceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//
//Route::apiResource('products', ProductResourceController::class);

Route::controller(\App\Http\Controllers\ProductController::class)->prefix('products')->group(function () {
    Route::get('', 'index')->name('products.index');
    Route::get('{product}', 'show')->name('products.show');

    Route::post('', 'store')->name('product.store');
    Route::post('{product}/review', 'review')->name('product.review.store');

    Route::put('{product}', 'update')->name('product.update');
    Route::patch('{product}', 'update')->name('product.patch');

    Route::delete('{product}', 'destroy')->name('product.destroy');
});

Route::controller(\App\Http\Controllers\UserController::class)->group(function (){
    Route::post('login', 'login')->name('login');
});
