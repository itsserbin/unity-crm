<?php

use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ClientsController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\SourcesController;
use App\Http\Controllers\Api\StatusesController;
use App\Http\Controllers\Api\StatusGroupsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductsController::class, 'index'])
            ->name('api.products.index');

        Route::post('create', [ProductsController::class, 'create'])
            ->name('api.products.create');

        Route::get('edit/{id}', [ProductsController::class, 'edit'])
            ->name('api.products.edit');

        Route::put('update/{id}', [ProductsController::class, 'update'])
            ->name('api.products.update');

        Route::delete('destroy/{id}', [ProductsController::class, 'destroy'])
            ->name('api.products.destroy');

        Route::get('list', [ProductsController::class, 'list'])
            ->name('api.products.list');

        Route::get('search={search}', [ProductsController::class, 'search'])
            ->name('api.products.search');
    });

    Route::prefix('categories')->group(function () {

        Route::get('/', [CategoriesController::class, 'index'])
            ->name('api.categories.index');

        Route::get('list', [CategoriesController::class, 'list'])
            ->name('api.categories.list');

        Route::post('create', [CategoriesController::class, 'create'])
            ->name('api.categories.create');

        Route::get('edit/{id}', [CategoriesController::class, 'edit'])
            ->name('api.categories.edit');

        Route::put('update/{id}', [CategoriesController::class, 'update'])
            ->name('api.categories.update');

        Route::delete('destroy/{id}', [CategoriesController::class, 'destroy'])
            ->name('api.categories.destroy');

        Route::get('search={search}', [CategoriesController::class, 'search'])
            ->name('api.categories.search');
    });

    Route::prefix('clients')->group(function () {
        Route::get('list', [ClientsController::class, 'list'])
            ->name('api.clients.list');

        Route::get('statuses', [ClientsController::class, 'statuses'])
            ->name('api.clients.statuses');

        Route::post('create', [ClientsController::class, 'create'])
            ->name('api.clients.create');

        Route::get('/', [ClientsController::class, 'index'])
            ->name('api.clients.index');

        Route::get('edit/{id}', [ClientsController::class, 'edit'])
            ->name('api.clients.edit');

        Route::put('update/{id}', [ClientsController::class, 'update'])
            ->name('api.clients.update');

        Route::get('search={search}', [ClientsController::class, 'search'])
            ->name('api.clients.search');

        Route::delete('/destroy/{id}', [ClientsController::class, 'destroy'])
            ->name('api.clients.destroy');
    });

    Route::prefix('sources')->group(function () {
        Route::get('list', [SourcesController::class, 'list'])
            ->name('api.sources.list');

        Route::post('create', [SourcesController::class, 'create'])
            ->name('api.sources.create');

        Route::get('/', [SourcesController::class, 'index'])
            ->name('api.sources.index');

        Route::get('edit/{id}', [SourcesController::class, 'edit'])
            ->name('api.sources.edit');

        Route::put('update/{id}', [SourcesController::class, 'update'])
            ->name('api.sources.update');

        Route::get('search={search}', [SourcesController::class, 'search'])
            ->name('api.sources.search');

        Route::delete('/destroy/{id}', [SourcesController::class, 'destroy'])
            ->name('api.sources.destroy');
    });

    Route::prefix('statuses')->group(function () {
        Route::get('list', [StatusesController::class, 'list'])
            ->name('api.statuses.list');

        Route::get('set-published', [StatusesController::class, 'setPublished'])
            ->name('api.statuses.set-published');

        Route::post('create', [StatusesController::class, 'create'])
            ->name('api.statuses.create');

        Route::get('/', [StatusesController::class, 'index'])
            ->name('api.statuses.index');

        Route::get('edit/{id}', [StatusesController::class, 'edit'])
            ->name('api.statuses.edit');

        Route::put('update/{id}', [StatusesController::class, 'update'])
            ->name('api.statuses.update');

        Route::delete('/destroy/{id}', [StatusesController::class, 'destroy'])
            ->name('api.statuses.destroy');
    });

    Route::prefix('status-groups')->group(function () {
        Route::get('list', [StatusGroupsController::class, 'list'])
            ->name('api.status-groups.list');

        Route::post('create', [StatusGroupsController::class, 'create'])
            ->name('api.status-groups.create');

        Route::get('/', [StatusGroupsController::class, 'index'])
            ->name('api.status-groups.index');

        Route::get('edit/{id}', [StatusGroupsController::class, 'edit'])
            ->name('api.status-groups.edit');

        Route::put('update/{id}', [StatusGroupsController::class, 'update'])
            ->name('api.status-groups.update');

        Route::delete('/destroy/{id}', [StatusGroupsController::class, 'destroy'])
            ->name('api.status-groups.destroy');
    });
});
