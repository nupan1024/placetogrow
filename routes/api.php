<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\Admin\MicrositeController;
use \App\Support\Http\Middleware\IsAdmin;

Route::get('/list-microsites', [MicrositeController::class, 'list'])->name('.microsites.list');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/admin-list-microsites', [MicrositeController::class, 'list'])->name('.admin.microsites.list');

Route::middleware(['auth:sanctum'])->group(function () {
});
