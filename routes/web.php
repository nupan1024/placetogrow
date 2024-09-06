<?php

use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\FieldsController;
use App\Http\Controllers\Web\Admin\ImportInvoiceController;
use App\Http\Controllers\Web\Admin\InvoiceController;
use App\Http\Controllers\Web\Admin\MicrositeController;
use App\Http\Controllers\Web\Admin\RolesController;
use App\Http\Controllers\Web\Admin\UserController;
use App\Http\Controllers\Web\HomeController;
use App\Support\Definitions\Permissions;
use App\Support\Http\Middleware\HasRole;
use App\Support\Http\Middleware\ProtectRoles;
use App\Support\Http\Middleware\ProtectSuperAdmin;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\InvoicesController as UserInvoiceController;
use Illuminate\Support\Facades\Session;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/greeting/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'es'])) {
        abort(400);
    }

    Session::put('locale', $locale);
    return redirect()->back();
})->name('locale');

Route::get('/form/microsite/{microsite}', [HomeController::class, 'formMicrosite'])->name('form.microsite');

Route::post('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
Route::get('/payment/{payment}/detail', [PaymentController::class, 'detail'])->name('payment.detail');

Route::middleware(['auth', 'verified', HasRole::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/fields', [FieldsController::class, 'index'])
        ->name('fields')
        ->middleware([Authorize::using(Permissions::FIELDS->value)]);

    Route::middleware([Authorize::using(Permissions::CREATE_FIELD->value)])
        ->group(function () {
            Route::get('/field/create', [FieldsController::class, 'create'])
                ->name('field.create');
            Route::post('/field/store', [FieldsController::class, 'store'])
                ->name('field.store');
        });

    Route::middleware([Authorize::using(Permissions::UPDATE_FIELD->value)])
        ->group(function () {
            Route::get('/field/{field}/edit', [FieldsController::class, 'edit'])
                ->name('field.edit');
            Route::post('/field/{field}/update', [FieldsController::class, 'update'])
                ->name('field.update');
        });

    Route::delete('/field/{field}/delete', [FieldsController::class, 'delete'])
        ->name('field.delete')
        ->middleware([Authorize::using(Permissions::DELETE_FIELD->value)]);

    Route::get('/invoices/imports', [InvoiceController::class, 'imports'])
        ->name('invoices.imports')
        ->middleware([Authorize::using(Permissions::INVOICES->value)]);

    Route::post('/invoices/import', [InvoiceController::class, 'import'])
        ->name('invoices.import')
        ->middleware([Authorize::using(Permissions::INVOICES->value)]);

    Route::get('/invoices', [InvoiceController::class, 'index'])
        ->name('invoices')
        ->middleware([Authorize::using(Permissions::INVOICES->value)]);

    Route::middleware([Authorize::using(Permissions::CREATE_INVOICE->value)])
        ->group(function () {
            Route::get('/invoice/create', [InvoiceController::class, 'create'])
                ->name('invoice.create');
            Route::post('/invoice/store', [InvoiceController::class, 'store'])
                ->name('invoice.store');
        });

    Route::middleware([Authorize::using(Permissions::UPDATE_INVOICE->value)])->group(function () {
        Route::get('/invoice/{invoice}/edit', [InvoiceController::class, 'edit'])
            ->name('invoice.edit');
        Route::post('/invoice/{invoice}/update', [InvoiceController::class, 'update'])
            ->name('invoice.update');
    });

    Route::delete('/invoice/{invoice}/delete', [InvoiceController::class, 'delete'])
        ->name('invoice.delete')
        ->middleware([Authorize::using(Permissions::DELETE_INVOICE->value)]);

    Route::get('/payments', [PaymentController::class, 'index'])
        ->name('payments')
        ->middleware([Authorize::using(Permissions::PAYMENTS->value)]);

    Route::get('/microsites', [MicrositeController::class, 'index'])
        ->name('microsites')
        ->middleware([Authorize::using(Permissions::MICROSITES->value)]);

    Route::middleware([Authorize::using(Permissions::CREATE_MICROSITE->value)])
        ->group(function () {
            Route::get('/create', [MicrositeController::class, 'create'])
                ->name('microsite.create');
            Route::post('/store', [MicrositeController::class, 'store'])
                ->name('microsite.store');
        });

    Route::middleware([Authorize::using(Permissions::UPDATE_MICROSITE->value)])
        ->group(function () {
            Route::get('/microsite/{microsite}/edit', [MicrositeController::class, 'edit'])
                ->name('microsite.edit');
            Route::patch('/microsite/{microsite}/update', [MicrositeController::class, 'update'])
                ->name('microsite.update');
        });

    Route::delete('/microsite/{microsite}/delete', [MicrositeController::class, 'delete'])
        ->name('microsite.delete')
        ->middleware(Authorize::using(Permissions::DELETE_MICROSITE->value));

    Route::get('/users', [UserController::class, 'index'])->name('users')
        ->middleware([Authorize::using(Permissions::USERS->value)]);

    Route::middleware([Authorize::using(Permissions::CREATE_USER->value)])
        ->group(function () {
            Route::get('/user/create', [UserController::class, 'create'])
                ->name('user.create');
            Route::post('/user/store', [UserController::class, 'store'])
                ->name('user.store');
        });

    Route::middleware([Authorize::using(Permissions::UPDATE_USER->value), ProtectSuperAdmin::class,])
        ->group(function () {
            Route::get('/user/{user}/edit', [UserController::class, 'edit'])
                ->name('user.edit');
            Route::patch('/user/{user}/update', [UserController::class, 'update'])
                ->name('user.update');
        });

    Route::delete('/user/{user}/delete', [UserController::class, 'delete'])
        ->name('user.delete')
        ->middleware([
            Authorize::using(Permissions::DELETE_USER->value),
            ProtectSuperAdmin::class,
        ]);

    Route::get('/roles', [RolesController::class, 'index'])
        ->name('roles')
        ->middleware(Authorize::using(Permissions::ROLES->value));

    Route::middleware([Authorize::using(Permissions::CREATE_ROLE->value)])
        ->group(function () {
            Route::get('/role/create', [RolesController::class, 'create'])
                ->name('role.create');
            Route::post('/role/store', [RolesController::class, 'store'])
                ->name('role.store');
        });

    Route::middleware([
        Authorize::using(Permissions::UPDATE_ROLE->value), ProtectRoles::class,
    ])->group(function () {
        Route::get('/role/{role}/edit', [RolesController::class, 'edit'])
            ->name('role.edit');
        Route::patch('/role/{role}/update', [RolesController::class, 'update'])
            ->name('role.update');
    });

    Route::delete('/role/{role}/delete', [RolesController::class, 'delete'])
        ->name('role.delete')
        ->middleware([
            Authorize::using(Permissions::DELETE_ROLE->value),
            ProtectRoles::class,
        ]);
});

Route::middleware('auth')->group(function () {
    $url = '/profile';
    Route::get($url, [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch($url, [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete($url, [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/user/invoices', [UserInvoiceController::class, 'index'])
        ->name('user.invoices.list');
    Route::get('/user/payments', [PaymentController::class, 'list'])
        ->name('user.payments.list');
});

require __DIR__.'/auth.php';
