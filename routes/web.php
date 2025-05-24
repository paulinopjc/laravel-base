<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Frontend\HomeController;

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/admin', function () {
//     return Inertia::render('auth/Login'); // assuming you use `resources/js/pages/auth/Login.vue`
// })->name('admin');

// Route::get('/admin/forgot-password', function () {
//     return Inertia::render('auth/ForgotPassword'); // assuming you use `resources/js/pages/auth/Login.vue`
// })->name('password');

// Route::get('/', function () {
//     return Inertia::render('Welcome'); // Or any page you want as home
// })->name('home');

/**
 * Redirect /admin to /admin/dashboard if authenticated,
 * otherwise to /admin/login
 */
Route::get('/admin', function () {
    // Change 'admin' guard here if you use a custom guard
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('admin.login');
});

Route::middleware([RedirectIfAuthenticated::class])->group(function () {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/admin/forgot-password', [PasswordResetLinkController::class, 'create'])->name('admin.password.request');
    Route::post('/admin/forgot-password', [PasswordResetLinkController::class, 'store'])->name('admin.password.email');
});

Route::middleware([AdminAuthenticate::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
