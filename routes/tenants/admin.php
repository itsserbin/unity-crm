<?php

use App\Http\Controllers\Tenants\Catalog\CategoriesController;
use App\Http\Controllers\Tenants\Catalog\ImagesController;
use App\Http\Controllers\Tenants\Catalog\ProductsController;
use App\Http\Controllers\Tenants\CRM\ClientsController;
use App\Http\Controllers\Tenants\CRM\OrdersController;
use App\Http\Controllers\Tenants\DashboardController;
use App\Http\Controllers\Tenants\Finance\AccountsController;
use App\Http\Controllers\Tenants\Options\DeliveryServicesController;
use App\Http\Controllers\Tenants\Options\RolesController;
use App\Http\Controllers\Tenants\Options\SourcesController;
use App\Http\Controllers\Tenants\Options\StatusesController;
use App\Http\Controllers\Tenants\Finance\BankAccountMovementsController;
use App\Http\Controllers\Tenants\Finance\BankAccountsController;
use App\Http\Controllers\Tenants\Finance\CashFlowController;
use App\Http\Controllers\Tenants\Finance\MovementCategoriesController;
use App\Http\Controllers\Tenants\Finance\ProfitAndLossController;
use App\Http\Controllers\Tenants\Options\UsersController;
use App\Http\Controllers\Tenants\ProfileController;
use App\Http\Controllers\Tenants\Statistics\OrderStatisticsController;
use App\Http\Controllers\Tenants\Statistics\ProfitStatisticsController;
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

    Route::prefix('finance')->group(function () {
        Route::get('bank-accounts', [BankAccountsController::class, 'create'])
            ->name('finance.bank-accounts');

        Route::get('bank-account-movements', [BankAccountMovementsController::class, 'create'])
            ->name('finance.bank-account-movements');

        Route::get('movement-categories', [MovementCategoriesController::class, 'create'])
            ->name('finance.movement-categories');

        Route::get('profit-and-loss', [ProfitAndLossController::class, 'create'])
            ->name('finance.profit-and-loss');

        Route::get('cash-flow', [CashFlowController::class, 'create'])
            ->name('finance.cash-flow');

        Route::get('accounts', [AccountsController::class, 'create'])
            ->name('finance.accounts');
    });

    Route::prefix('options')->group(function () {
        Route::get('sources', [SourcesController::class, 'create'])
            ->name('options.sources');

        Route::get('statuses', [StatusesController::class, 'create'])
            ->name('options.statuses');

        Route::get('delivery-services', [DeliveryServicesController::class, 'create'])
            ->name('options.delivery-services');

        Route::get('users', [UsersController::class, 'create'])
            ->name('options.users');

        Route::get('roles', [RolesController::class, 'create'])
            ->name('options.roles');
    });

    Route::prefix('statistics')->group(function () {
        Route::get('orders', [OrderStatisticsController::class, 'create'])
            ->name('statistics.orders');

        Route::get('profits', [ProfitStatisticsController::class, 'create'])
            ->name('statistics.profits');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::delete('/', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');
    });
});
