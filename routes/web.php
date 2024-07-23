<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\MicrositeController;
use App\Http\Controllers\Web\Admin\RolesController;
use App\Http\Controllers\Web\Admin\UserController;
use App\Http\Controllers\Web\HomeController;
use App\Support\Definitions\Permissions;
use App\Support\Http\Middleware\ClearMicrositeCache;
use App\Support\Http\Middleware\ClearUserCache;
use App\Support\Http\Middleware\IsAdmin;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/form-microsite/{microsite}', [HomeController::class, 'formMicrosite'])
    ->name('micrositio.form');

Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/microsites', [MicrositeController::class, 'index'])
        ->name('microsites')
        ->middleware([Authorize::using(Permissions::MICROSITES->value)]);

    Route::middleware([Authorize::using(Permissions::CREATE_MICROSITE->value)])->group(function () {
        Route::get('/create', [MicrositeController::class, 'create'])->name('microsite.create');
        Route::post('/store', [MicrositeController::class, 'store'])->name('microsite.store')
            ->middleware([ClearMicrositeCache::class]);
    });

    Route::middleware([Authorize::using(Permissions::UPDATE_MICROSITE->value)])->group(function () {
        Route::get('/edit/{microsite}', [MicrositeController::class, 'edit'])->name('microsite.edit');
        Route::patch('/update-microsite/{microsite}', [MicrositeController::class, 'update'])
            ->name('microsite.update')->middleware([ClearMicrositeCache::class]);
    });

    Route::delete('/delete-microsite/{microsite}', [MicrositeController::class, 'delete'])
        ->name('microsite.delete')
        ->middleware([
            ClearMicrositeCache::class,
            Authorize::using(Permissions::DELETE_MICROSITE->value)
        ]);

    Route::get('/users', [UserController::class, 'index'])->name('users')
        ->middleware([Authorize::using(Permissions::USERS->value)]);

    Route::middleware([Authorize::using(Permissions::CREATE_USER->value)])->group(function () {
        Route::get('/user-create', [UserController::class, 'create'])
            ->name('user.create');
        Route::post('/user-store', [UserController::class, 'store'])
            ->name('user.store')->middleware([ClearUserCache::class]);
    });

    Route::middleware([Authorize::using(Permissions::UPDATE_USER->value)])->group(function () {
        Route::get('/users-edit/{user}', [UserController::class, 'edit'])->name('user.edit');
        Route::patch('/users-update/{user}', [UserController::class, 'update'])
            ->name('user.update')->middleware(ClearUserCache::class);
    });


    Route::delete('/users-delete/{user}', [UserController::class, 'delete'])
        ->name('user.delete')
        ->middleware([ClearUserCache::class, Authorize::using(Permissions::DELETE_USER->value)]);

    Route::get('/roles', [RolesController::class, 'index'])
        ->name('roles')->middleware(Authorize::using(Permissions::ROLES->value));

    Route::middleware([Authorize::using(Permissions::CREATE_ROLE->value)])->group(function () {
        Route::get('/create-role', [RolesController::class, 'create'])->name('roles.create');
        Route::post('/store-role', [RolesController::class, 'store'])->name('roles.store');
    });

    Route::middleware([Authorize::using(Permissions::UPDATE_ROLE->value)])->group(function () {
        Route::get('/edit-role/{role}', [RolesController::class, 'edit'])->name('roles.edit');
        Route::patch('/update-role/{role}', [RolesController::class, 'update'])->name('roles.update');
    });

    Route::delete('/delete-role/{role}', [RolesController::class, 'delete'])
        ->name('roles.delete')->middleware(Authorize::using(Permissions::DELETE_ROLE->value));

});

Route::middleware('auth')->group(function () {
    $url = '/profile';
    Route::get($url, [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch($url, [ProfileController::class, 'update'])->name('profile.update');
    Route::delete($url, [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
