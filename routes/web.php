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
    Route::get('/view-create-microsite', [MicrositeController::class, 'viewCreate'])->name('microsite.viewCreate');
    Route::post('/create-microsite', [MicrositeController::class, 'create'])->name('microsite.create');

    Route::get('/edit-microsite/{id}', [MicrositeController::class, 'viewUpdate'])->name('microsite.viewUpdate');
    Route::post('/update-microsite', [MicrositeController::class, 'edit'])->name('microsite.update');

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
