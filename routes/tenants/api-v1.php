<?php

use App\Http\Controllers\Api\V1\OrdersController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('orders')->group(function () {
        Route::post('create', [OrdersController::class, 'create'])
            ->name('api.v1.orders.create');
    });
});
