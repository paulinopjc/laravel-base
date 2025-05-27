<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\SiteLogoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('settings', '/admin/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('settings.profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('settings.password.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('Settings/Appearance');
    })->name('settings.appearance');

    Route::get('settings/logos', [SiteLogoController::class, 'edit'])->name('settings.logos.edit');
    Route::post('settings/logos', [SiteLogoController::class, 'update'])->name('settings.logos.update');

});
