<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::middleware('auth')->group(function () {
//     Route::redirect('settings', '/settings/profile');

//     Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//     Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
//     Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

//     Route::get('settings/appearance', function () {
//         return Inertia::render('settings/Appearance');
//     })->name('appearance');
// });

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
});
