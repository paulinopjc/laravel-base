<?php

use App\Http\Controllers\Admin\FooterLinkController;
use App\Models\FooterLink;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Admin\CMSPageController;
use App\Http\Controllers\Admin\MenuController;
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

Route::middleware([AdminAuthenticate::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserController::class)
            ->middleware(['auth', 'verified'])
            ->names('users');

        Route::resource('user-roles', UserRoleController::class)
            ->middleware(['auth', 'verified'])
            ->names('user-roles');

        Route::resource('cms-pages', CMSPageController::class)
            ->middleware(['auth', 'verified'])
            ->names('cms-pages');

        Route::resource('menus', MenuController::class)
            ->middleware(['auth', 'verified'])
            ->names('menus');

        Route::resource('footer-links', FooterLinkController::class)
            ->middleware(['auth', 'verified'])
            ->names('footer-links');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';