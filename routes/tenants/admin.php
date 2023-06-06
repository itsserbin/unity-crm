<?php

use App\Http\Controllers\Tenants\Catalog\CategoriesController;
use App\Http\Controllers\Tenants\Catalog\ImagesController;
use App\Http\Controllers\Tenants\Catalog\ProductsController;
use App\Http\Controllers\Tenants\CRM\ClientsController;
use App\Http\Controllers\Tenants\CRM\OrdersController;
use App\Http\Controllers\Tenants\DashboardController;
use App\Http\Controllers\Tenants\Options\DeliveryServicesController;
use App\Http\Controllers\Tenants\Options\SourcesController;
use App\Http\Controllers\Tenants\Options\StatusesController;
use App\Http\Controllers\Tenants\Statistics\BankAccountMovementsController;
use App\Http\Controllers\Tenants\Statistics\BankAccountsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'create'])
        ->name('dashboard');

    Route::prefix('catalog')->group(function () {
        Route::get('products', [ProductsController::class, 'create'])
            ->name('catalog.products');

        Route::get('categories', [CategoriesController::class, 'create'])
            ->name('catalog.categories');

        Route::get('images', [ImagesController::class, 'create'])
            ->name('catalog.images');
    });

    Route::prefix('crm')->group(function () {
        Route::get('clients', [ClientsController::class, 'create'])
            ->name('crm.clients');

        Route::get('orders', [OrdersController::class, 'create'])
            ->name('crm.orders');
    });

    Route::prefix('statistics')->group(function () {
        Route::get('bank-accounts', [BankAccountsController::class, 'create'])
            ->name('statistics.bank-accounts');

        Route::get('bank-account-movements', [BankAccountMovementsController::class, 'create'])
            ->name('statistics.bank-account-movements');
    });

    Route::prefix('options')->group(function () {
        Route::get('sources', [SourcesController::class, 'create'])
            ->name('options.sources');

        Route::get('statuses', [StatusesController::class, 'create'])
            ->name('options.statuses');

        Route::get('delivery-services', [DeliveryServicesController::class, 'create'])
            ->name('options.delivery-services');
    });
});
