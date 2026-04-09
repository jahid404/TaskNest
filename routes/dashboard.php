<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\TaskController;

Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Tasks
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/modify/{task?}', [TaskController::class, 'modify'])->name('modify');
        Route::post('/save/{task?}', [TaskController::class, 'save'])->name('save');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
    });

    // Profile (Fallback/Consistent access)
    Route::get('/profile', [SettingsController::class, 'index'])->name('profile');
});
