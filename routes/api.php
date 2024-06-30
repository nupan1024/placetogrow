<?php

use App\Http\Controllers\Api\Admin\MicrositeController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\MicrositeController as UserApiMicrositeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/list-microsites', [UserApiMicrositeController::class, 'list'])->name('.microsites.list');
Route::post('/get-token', [MicrositeController::class, 'getToken'])->name('.getToken');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/admin-list-microsites', [MicrositeController::class, 'list'])->name('.admin.microsites.list');
    Route::get('/list-users', [UserController::class, 'list'])->name('.users.list');
});
