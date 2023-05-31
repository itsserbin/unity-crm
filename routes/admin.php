<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('index');

Route::middleware(['auth'])->group(function () {
//Route::middleware(['auth','verified'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'dashboard'])
        ->name('dashboard');
});
