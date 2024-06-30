<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\MicrositeController;
use App\Http\Controllers\Web\HomeController;
use App\Support\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/microsites', [MicrositeController::class, 'index'])->name('microsites');
    Route::get('/create', [MicrositeController::class, 'create'])->name('microsite.create');
    Route::post('/store', [MicrositeController::class, 'store'])->name('microsite.store');

    Route::get('/edit/{id}', [MicrositeController::class, 'edit'])->name('microsite.edit');
    Route::patch('/update-microsite/{id}', [MicrositeController::class, 'update'])->name('microsite.update');

    Route::delete('/delete-microsite/{id}', [MicrositeController::class, 'delete'])->name('microsite.delete');

    Route::get('/create-user', [DashboardController::class, 'index'])->name('user.create');
    Route::get('/roles', [DashboardController::class, 'index'])->name('roles');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
