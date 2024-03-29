<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::get('register', [RegisteredUserController::class, 'create'])
    ->name('register');

Route::post('register', [RegisteredUserController::class, 'store']);

Route::post('new-order', function (\Illuminate\Http\Request $request) {
    Log::info('Вхідні дані форми:', $request->all());
});

require __DIR__ . '/api.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
