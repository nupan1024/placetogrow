<?php

use App\Domain\Imports\Actions\CreateImport;
use App\Domain\Imports\Models\Import;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\Role;
use App\Domain\Users\Models\User;
use App\Jobs\ProcessImportInvoices;
use App\Support\Definitions\ImportStatus;
use App\Support\Definitions\Roles;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

test('job can process import invoices', function () {
    Storage::fake(Import::DISK);
    Role::factory()->create([
        'id' => Roles::GUEST->value,
        'name' => Roles::GUEST->name,
        'guard_name' => 'web',
    ]);

    Role::factory()->create([
        'id' => Roles::SUPER_ADMIN->value,
        'name' => Roles::SUPER_ADMIN->name,
        'guard_name' => 'web',
    ]);
    $params = [
        'file' => new UploadedFile('./tests/Stubs/invoices.csv', 'invoices.csv'),
        'status' => fake()->randomElement(ImportStatus::toArray()),
        'errors' => [],
    ];

    Auth::login(User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]));
    $microsite = Microsite::factory()->create();
    $import = CreateImport::execute($params);
    $job = new ProcessImportInvoices($import, $microsite->id);
    $job->handle();

    $this->assertDatabaseHas('invoices', [
        'code' => 'MICROSITE_PLACETOGROW_667755',
    ]);
});

test('job can  not process import invoices because file has errors', function () {
    Storage::fake(Import::DISK);
    Role::factory()->create([
        'id' => Roles::GUEST->value,
        'name' => Roles::GUEST->name,
        'guard_name' => 'web',
    ]);

    Role::factory()->create([
        'id' => Roles::SUPER_ADMIN->value,
        'name' => Roles::SUPER_ADMIN->name,
        'guard_name' => 'web',
    ]);
    $params = [
        'file' => new UploadedFile('./tests/Stubs/bad_invoices.csv', 'invoices.csv'),
        'status' => fake()->randomElement(ImportStatus::toArray()),
        'errors' => [],
    ];

    Auth::login(User::factory()->create([
        'role_id' => Roles::SUPER_ADMIN->value,
    ]));
    $microsite = Microsite::factory()->create();
    $import = CreateImport::execute($params);
    $job = new ProcessImportInvoices($import, $microsite->id);
    $job->handle();

    $this->assertDatabaseHas('imports', [
        'status' => ImportStatus::FAILED->value,
    ]);

});
