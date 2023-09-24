<?php

use App\Http\Controllers\Api\TenantsController;
use App\Http\Controllers\Api\V1\OrdersController;
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

Route::middleware('auth:sanctum')->prefix('api')->group(function () {

    Route::prefix('tenants')->group(function () {
        Route::get('/', [TenantsController::class, 'index'])
            ->name('api.tenants.index');

        Route::post('create', [TenantsController::class, 'create'])
            ->name('api.tenants.create');

        Route::get('edit/{id}', [TenantsController::class, 'edit'])
            ->name('api.tenants.edit');

        Route::put('update/{id}', [TenantsController::class, 'update'])
            ->name('api.tenants.update');

        Route::delete('destroy/{id}', [TenantsController::class, 'destroy'])
            ->name('api.tenants.destroy');
    });
});
