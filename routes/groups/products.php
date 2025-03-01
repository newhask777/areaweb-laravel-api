<?php

use Illuminate\Support\Facades\Route;

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
