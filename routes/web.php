<?php

use Illuminate\Support\Facades\Route;

// Standard Controller
Route::get('/', [App\Http\Controllers\Controller::class, 'welcome'])->name('welcome');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\DashboardController::class, 'home'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/filter', [App\Http\Controllers\DashboardController::class, 'filtering'])->name('filter.get');
});

Route::middleware(['auth', 'export.validation'])->group(function () {
    Route::post('/export', [App\Http\Controllers\DashboardController::class, 'export'])->name('export');
});

require __DIR__ . '/auth.php';
