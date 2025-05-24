<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\RedirectIfAuthenticated;

Route::middleware([RedirectIfAuthenticated::class])->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('admin.login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('admin.login.store');

    Route::get('forgot-password', [AuthenticatedSessionController::class, 'createForgotPassword'])
        ->name('admin.password.request');

    Route::post('forgot-password', [AuthenticatedSessionController::class, 'storeForgotPassword'])
        ->name('admin.password.email');
});

Route::middleware([AdminAuthenticate::class])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';