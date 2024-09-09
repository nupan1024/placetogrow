<?php

use App\Http\Controllers\Api\Admin\FieldsController;
use App\Http\Controllers\Api\Admin\InvoicesController;
use App\Http\Controllers\Api\Admin\MicrositeController;
use App\Http\Controllers\Api\Admin\PaymentController;
use App\Http\Controllers\Api\Admin\SubscriptionController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\MicrositeController as UserApiMicrositeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/microsites', [UserApiMicrositeController::class, 'index'])->name('.microsites');
Route::post('/getToken', [MicrositeController::class, 'getToken'])->name('.getToken');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/admin/microsites', [MicrositeController::class, 'index'])->name('.admin.microsites');
    Route::get('/admin/microsite/{microsite}/subscriptions', [MicrositeController::class, 'subscriptions'])->name('.admin.microsite.subscriptions');
    Route::get('/admin/microsite/{microsite}/invoices', [MicrositeController::class, 'invoices'])->name('.admin.microsite.invoices');
    Route::get('/admin/users', [UserController::class, 'index'])->name('.admin.users');
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('.admin.roles');
    Route::get('/admin/payments', [PaymentController::class, 'index'])->name('.admin.payments');
    Route::get('/admin/fields', [FieldsController::class, 'index'])->name('.admin.fields');
    Route::get('/admin/invoices', [InvoicesController::class, 'index'])->name('.admin.invoices');
    Route::get('/admin/invoices/imports', [InvoicesController::class, 'imports'])->name('.admin.invoices.imports');
    Route::get('/user/{user}/invoices', [UserController::class, 'invoices'])->name('.user.invoices');
    Route::get('/user/{user}/payments', [UserController::class, 'payments'])->name('.user.payments');
    Route::get('/user/{user}/subscriptions', [UserController::class, 'subscriptions'])->name('.user.subscriptions');
    Route::get('/admin/subscriptions', [SubscriptionController::class, 'index'])->name('.admin.subscriptions');

});
