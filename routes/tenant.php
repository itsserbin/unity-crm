<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

//InitializeTenancyByDomain::$onFail = function ($exception, $request, $next) {
//    return redirect(\route('index'));
//};

Route::middleware(['web', InitializeTenancyByDomain::class, PreventAccessFromCentralDomains::class,])->group(function () {

    Route::get('/', [AuthenticatedSessionController::class, 'create'])
        ->middleware('guest');

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

    require __DIR__ . '/tenants/api.php';
    require __DIR__ . '/auth.php';
    require __DIR__ . '/tenants/admin.php';

    Route::get('register', function () {
        abort(404);
    });

    Route::post('register', function () {
        abort(404);
    });
});
