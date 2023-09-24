<?php

use App\Http\Controllers\Api\Tenants\Catalog\CategoriesController;
use App\Http\Controllers\Api\Tenants\Catalog\ImagesController;
use App\Http\Controllers\Api\Tenants\Catalog\ProductsController;
use App\Http\Controllers\Api\Tenants\CRM\ClientsController;
use App\Http\Controllers\Api\Tenants\CRM\OrdersController;
use App\Http\Controllers\Api\Tenants\CRM\TrackingCodesController;
use App\Http\Controllers\Api\Tenants\Finance\AccountsController;
use App\Http\Controllers\Api\Tenants\MonobankController;
use App\Http\Controllers\Api\Tenants\Options\DeliveryServicesController;
use App\Http\Controllers\Api\Tenants\Options\SourcesController;
use App\Http\Controllers\Api\Tenants\Options\StatusesController;
use App\Http\Controllers\Api\Tenants\Options\StatusGroupsController;
use App\Http\Controllers\Api\Tenants\Finance\BankAccountMovementsController;
use App\Http\Controllers\Api\Tenants\Finance\BankAccountsController;
use App\Http\Controllers\Api\Tenants\Finance\CashFlowController;
use App\Http\Controllers\Api\Tenants\Finance\MovementCategoriesController;
use App\Http\Controllers\Api\Tenants\Finance\ProfitAndLossController;
use App\Http\Controllers\Api\Tenants\ProfileController;
use App\Http\Controllers\Api\Tenants\UploadController;
use App\Http\Controllers\Api\Tenants\UsersController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

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

        Route::post('create-client-address/{id}', [ClientsController::class, 'createClientAddress'])
            ->name('api.clients.create-client-address');

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

    Route::prefix('orders')->group(function () {
        Route::post('create', [OrdersController::class, 'create'])
            ->name('api.orders.create');

        Route::get('/', [OrdersController::class, 'index'])
            ->name('api.orders.index');

        Route::get('edit/{id}', [OrdersController::class, 'edit'])
            ->name('api.orders.edit');

        Route::put('update/{id}', [OrdersController::class, 'update'])
            ->name('api.orders.update');

        Route::get('search={search}', [OrdersController::class, 'search'])
            ->name('api.orders.search');

        Route::delete('/destroy/{id}', [OrdersController::class, 'destroy'])
            ->name('api.orders.destroy');
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

        Route::post('set-published', [StatusesController::class, 'setPublished'])
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

    Route::prefix('delivery-services')->group(function () {
        Route::get('list', [DeliveryServicesController::class, 'list'])
            ->name('api.delivery-services.list');

        Route::post('set-published', [DeliveryServicesController::class, 'setPublished'])
            ->name('api.delivery-services.set-published');

        Route::post('create', [DeliveryServicesController::class, 'create'])
            ->name('api.delivery-services.create');

        Route::get('/', [DeliveryServicesController::class, 'index'])
            ->name('api.delivery-services.index');

        Route::get('edit/{id}', [DeliveryServicesController::class, 'edit'])
            ->name('api.delivery-services.edit');

        Route::put('update/{id}', [DeliveryServicesController::class, 'update'])
            ->name('api.delivery-services.update');

        Route::delete('/destroy/{id}', [DeliveryServicesController::class, 'destroy'])
            ->name('api.delivery-services.destroy');
    });

    Route::prefix('users')->group(function () {
        Route::get('list', [UsersController::class, 'list'])
            ->name('api.users.list');

        Route::post('create', [UsersController::class, 'create'])
            ->name('api.users.create');

        Route::get('/', [UsersController::class, 'index'])
            ->name('api.users.index');

        Route::get('edit/{id}', [UsersController::class, 'edit'])
            ->name('api.users.edit');

        Route::put('update/{id}', [UsersController::class, 'update'])
            ->name('api.users.update');

        Route::delete('/destroy/{id}', [UsersController::class, 'destroy'])
            ->name('api.users.destroy');

        Route::prefix('tokens')->group(function () {
            Route::get('list', [ProfileController::class, 'getAllUserTokens'])
                ->name('api.user.tokens.list');

            Route::get('edit/{id}', [ProfileController::class, 'getTokenById'])
                ->name('api.user.tokens.edit');

            Route::put('update/{id}', [ProfileController::class, 'updateToken'])
                ->name('api.user.tokens.update');

            Route::post('create', [ProfileController::class, 'createToken'])
                ->name('api.user.tokens.create');

            Route::delete('destroy/{id}', [ProfileController::class, 'destroyTokenById'])
                ->name('api.user.tokens.destroy');
        });
    });

    Route::prefix('tracking-codes')->group(function () {
        Route::post('create', [TrackingCodesController::class, 'create'])
            ->name('api.tracking-codes.create');

        Route::get('/', [TrackingCodesController::class, 'index'])
            ->name('api.tracking-codes.index');

        Route::get('edit/{id}', [TrackingCodesController::class, 'edit'])
            ->name('api.tracking-codes.edit');

        Route::put('update/{id}', [TrackingCodesController::class, 'update'])
            ->name('api.tracking-codes.update');

        Route::delete('/destroy/{id}', [TrackingCodesController::class, 'destroy'])
            ->name('api.tracking-codes.destroy');
    });

    Route::prefix('images')->group(function () {
        Route::get('/', [ImagesController::class, 'index'])
            ->name('api.images.index');

        Route::get('edit/{id}', [ImagesController::class, 'edit'])
            ->name('api.images.edit');

        Route::put('update/{id}', [ImagesController::class, 'update'])
            ->name('api.images.update');

        Route::delete('/destroy/{id}', [ImagesController::class, 'destroy'])
            ->name('api.images.destroy');
    });

    Route::prefix('accounts')->group(function () {
        Route::get('/', [AccountsController::class, 'index'])
            ->name('api.accounts.index');

        Route::get('edit/{id}', [AccountsController::class, 'edit'])
            ->name('api.accounts.edit');

        Route::put('update/{id}', [AccountsController::class, 'update'])
            ->name('api.accounts.update');

        Route::post('create', [AccountsController::class, 'create'])
            ->name('api.accounts.create');

        Route::delete('/destroy/{id}', [AccountsController::class, 'destroy'])
            ->name('api.accounts.destroy');

        Route::get('list', [AccountsController::class, 'list'])
            ->name('api.accounts.list');
    });

    Route::prefix('bank-accounts')->group(function () {
        Route::get('/', [BankAccountsController::class, 'index'])
            ->name('api.bank-accounts.index');

        Route::get('edit/{id}', [BankAccountsController::class, 'edit'])
            ->name('api.bank-accounts.edit');

        Route::put('update/{id}', [BankAccountsController::class, 'update'])
            ->name('api.bank-accounts.update');

        Route::post('create', [BankAccountsController::class, 'create'])
            ->name('api.bank-accounts.create');

        Route::delete('/destroy/{id}', [BankAccountsController::class, 'destroy'])
            ->name('api.bank-accounts.destroy');

        Route::get('list', [BankAccountsController::class, 'list'])
            ->name('api.bank-accounts.list');
    });

    Route::prefix('movement-categories')->group(function () {
        Route::get('/', [MovementCategoriesController::class, 'index'])
            ->name('api.movement-categories.index');

        Route::get('edit/{id}', [MovementCategoriesController::class, 'edit'])
            ->name('api.movement-categories.edit');

        Route::put('update/{id}', [MovementCategoriesController::class, 'update'])
            ->name('api.movement-categories.update');

        Route::post('create', [MovementCategoriesController::class, 'create'])
            ->name('api.movement-categories.create');

        Route::delete('/destroy/{id}', [MovementCategoriesController::class, 'destroy'])
            ->name('api.movement-categories.destroy');

        Route::get('list', [MovementCategoriesController::class, 'list'])
            ->name('api.movement-categories.list');
    });

    Route::prefix('bank-accounts-movements')->group(function () {
        Route::get('/', [BankAccountMovementsController::class, 'index'])
            ->name('api.bank-account-movements.index');

        Route::get('edit/{id}', [BankAccountMovementsController::class, 'edit'])
            ->name('api.bank-account-movements.edit');

        Route::put('update/{id}', [BankAccountMovementsController::class, 'update'])
            ->name('api.bank-account-movements.update');

        Route::post('create', [BankAccountMovementsController::class, 'create'])
            ->name('api.bank-account-movements.create');

        Route::post('mass-create', [BankAccountMovementsController::class, 'massCreate'])
            ->name('api.bank-account-movements.mass-create');

        Route::delete('/destroy/{id}', [BankAccountMovementsController::class, 'destroy'])
            ->name('api.bank-account-movements.destroy');
    });

    Route::get('profit-and-loss', [ProfitAndLossController::class, 'index'])
        ->name('api.profit-and-loss.index');

    Route::get('cash-flow', [CashFlowController::class, 'index'])
        ->name('api.cash-flow.index');

    Route::prefix('upload')->group(function () {
        Route::post('product-preview', [UploadController::class, 'uploadProductPreview'])
            ->name('api.upload.product-preview');
    });

    Route::prefix('monobank')->group(function () {
        Route::post('client-info/{token}', [MonobankController::class, 'clientInfo'])
            ->name('api.monobank.client-info');

        Route::post('extract', [MonobankController::class, 'extract'])
            ->name('api.monobank.extract');
    });
});

Route::prefix('v1')->group(function () {
    Route::prefix('orders')->group(function () {
        Route::post( 'create', [OrdersController::class, 'create'])
            ->name('api.v1.orders.create');
    });
});
