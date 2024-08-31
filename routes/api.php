<?php

use App\Http\Controllers\Api\Admin\FieldsController;
use App\Http\Controllers\Api\Admin\InvoicesController;
use App\Http\Controllers\Api\Admin\MicrositeController;
use App\Http\Controllers\Api\Admin\PaymentController;
use App\Http\Controllers\Api\PaymentController as UserPaymentController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\InvoicesController as UserInvoicesController;
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
    Route::get('/admin/users', [UserController::class, 'index'])->name('.admin.users');
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('.admin.roles');
    Route::get('/admin/payments', [PaymentController::class, 'index'])->name('.admin.payments');
    Route::get('/admin/fields', [FieldsController::class, 'index'])->name('.admin.fields');
    Route::get('/admin/invoices', [InvoicesController::class, 'index'])->name('.admin.invoices');
    Route::get('/user/{user}/invoices', [UserInvoicesController::class, 'index'])->name('.user.invoices');
    Route::get('/user/{user}/payments', [UserPaymentController::class, 'index'])->name('.user.payments');
});
