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

Route::get('/list-microsites', [UserApiMicrositeController::class, 'list'])->name('.microsites.list');
Route::post('/get-token', [MicrositeController::class, 'getToken'])->name('.getToken');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/admin-list-microsites', [MicrositeController::class, 'list'])->name('.admin.microsites.list');
    Route::get('/list-users', [UserController::class, 'list'])->name('.users.list');
    Route::get('/list-roles', [RoleController::class, 'list'])->name('.roles.list');
    Route::get('/list-payments', [PaymentController::class, 'list'])->name('.payments.list');
    Route::get('/list-fields', [FieldsController::class, 'list'])->name('.fields.list');
    Route::get('/list-invoices', [InvoicesController::class, 'list'])->name('.invoices.list');
    Route::get('/list-invoices-user/{user}', [UserInvoicesController::class, 'list'])->name('.invoices.listUser');
    Route::get('/list-payments-user/{user}', [UserPaymentController::class, 'list'])->name('.payments.listUser');
});
