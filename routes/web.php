<?php

use App\Http\Controllers\ProfileController;
use  \App\Support\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\Admin\DashboardController;
use \App\Http\Controllers\Web\Admin\MicrositesController;

Route::get('/', [HomeController::class, 'index']);

Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/microsites', [MicrositesController::class, 'index'])->name('microsites');
    Route::get('/create-microsite', [MicrositesController::class, 'create'])->name('microsite.create');
    Route::get('/store-microsite', [MicrositesController::class, 'store'])->name('microsite.store');

    Route::get('/edit-microsite/{id}', [MicrositesController::class, 'edit'])->name('microsite.edit');
    Route::put('/delete-microsite/{id}', [MicrositesController::class, 'delete'])->name('microsite.delete');

    Route::get('/create-user', [DashboardController::class, 'index'])->name('user.create');
    Route::get('/roles', [DashboardController::class, 'index'])->name('roles');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
