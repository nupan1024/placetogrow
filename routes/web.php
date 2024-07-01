<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\MicrositeController;
use App\Http\Controllers\Web\Admin\RolesController;
use App\Http\Controllers\Web\Admin\UserController;
use App\Http\Controllers\Web\HomeController;
use App\Support\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/microsites', [MicrositeController::class, 'index'])->name('microsites');
    Route::get('/create', [MicrositeController::class, 'create'])->name('microsite.create');
    Route::post('/store', [MicrositeController::class, 'store'])->name('microsite.store');

    Route::get('/edit/{id}', [MicrositeController::class, 'edit'])->name('microsite.edit');
    Route::patch('/update-microsite/{id}', [MicrositeController::class, 'update'])->name('microsite.update');

    Route::delete('/delete-microsite/{id}', [MicrositeController::class, 'delete'])->name('microsite.delete');

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/user-create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user-store', [UserController::class, 'store'])->name('user.store');

    Route::get('/users-edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('/users-update/{id}', [UserController::class, 'update'])->name('user.update');

    Route::delete('/users-delete/{id}', [UserController::class, 'delete'])->name('user.delete');

    Route::get('/roles', [RolesController::class, 'index'])->name('roles');
});

Route::middleware('auth')->group(function () {
    $url = '/profile';
    Route::get($url, [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch($url, [ProfileController::class, 'update'])->name('profile.update');
    Route::delete($url, [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
