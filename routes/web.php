<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Controller::class, 'welcome'])->name('welcome');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\Controller::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/filter', [App\Http\Controllers\DashboardController::class, 'filtering'])->name('filter.get');
    Route::get('/updates', [App\Http\Controllers\UpdatesController::class, 'index'])->name('updates');
    Route::post('/updates/rule/add', [App\Http\Controllers\UpdatesController::class, 'addUpdateRule'])->name('rule.add');

    Route::middleware(['export.validation'])->group(function () {
        Route::post('/export', [App\Http\Controllers\DashboardController::class, 'export'])->name('export');
    });
});

require __DIR__ . '/auth.php';
