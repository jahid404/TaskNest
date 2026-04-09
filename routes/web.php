<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Redirect to dashboard
Route::get('/', function () {
    if (!Auth::check()) return redirect()->route('login');
    return redirect()->route('dashboard.index');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Settings & Profile
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/profile', [SettingsController::class, 'index'])->name('profile');
    Route::patch('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::patch('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
});

/*
|--------------------------------------------------------------------------
| Error Pages
|--------------------------------------------------------------------------
*/
Route::get('/error-404', function () {
    return view('pages.misc.error-404', ['title' => 'Error 404']);
})->name('error-404');

/**
 * Additional routes
 */
require __DIR__ . '/dashboard.php';
