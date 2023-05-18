<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [HomeController::class, 'dashboard'])
        ->name('dashboard');

    Route::prefix('content')->group(function () {
        Route::get('products', [HomeController::class, 'products'])
            ->name('products');

        Route::get('categories', [HomeController::class, 'categories'])
            ->name('categories');
    });
});
