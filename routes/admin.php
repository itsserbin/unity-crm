<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [HomeController::class, 'dashboard'])
        ->name('dashboard');

    Route::prefix('catalog')->group(function () {
        Route::get('products', [HomeController::class, 'products'])
            ->name('catalog.products');

        Route::get('categories', [HomeController::class, 'categories'])
            ->name('catalog.categories');
    });

    Route::prefix('crm')->group(function () {
        Route::get('clients', [HomeController::class, 'clients'])
            ->name('crm.clients');

        Route::get('orders', [HomeController::class, 'orders'])
            ->name('crm.orders');

    });

    Route::prefix('options')->group(function () {
        Route::get('sources', [HomeController::class, 'sources'])
            ->name('options.sources');

        Route::get('statuses', [HomeController::class, 'statuses'])
            ->name('options.statuses');
    });
});
