<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', [AuthenticatedSessionController::class, 'create'])
        ->middleware('guest');

//    Route::get('dashboard', [HomeController::class, 'dashboard'])
//        ->middleware('auth')
//        ->name('dashboard');
//
//    Route::get('products', [HomeController::class, 'products'])
//        ->middleware('auth')
//        ->name('products');
//
//    Route::get('categories', [HomeController::class, 'categories'])
//        ->middleware('auth')
//        ->name('categories');

    Route::get('images/{slug}', function ($slug) {
        try {
            if (env('APP_ENV') !== 'local') {
                $path = tenancy()->tenant->id . '/images/';
            } else {
                $path = 'local/' . tenancy()->tenant->id . '/images/';
            }
            return Storage::disk('s3')->response($path . $slug);
        } catch (Exception $exception) {
            abort(404);
        }
    })->name('images');

    require __DIR__ . '/api.php';
    require __DIR__ . '/auth.php';
    require __DIR__ . '/admin.php';
});
