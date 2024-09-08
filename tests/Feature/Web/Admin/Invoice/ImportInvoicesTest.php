<?php

use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use App\Jobs\ProcessImportInvoices;
use App\Domain\Imports\Models\Import;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

test('it can import invoices', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role);

    Storage::fake(Import::DISK);
    Queue::fake();

    $params = [
        'file' => UploadedFile::fake()->createWithContent(
            'invoices.csv',
            Storage::get('./tests/Stubs/invoices.csv')
        ),
        'microsite_id' => Microsite::factory()->create()->id,
    ];

    $this->actingAs($user)->post(route('invoices.import'), $params)
        ->assertRedirect(route('invoices.imports'));

    Queue::assertPushed(ProcessImportInvoices::class);

    $this->assertDatabaseHas('imports', [
        'user_id' => $user->id,
    ]);
});

test('it can not import invoices because file is not valid', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole($role);

    Storage::fake(Import::DISK);
    Queue::fake();

    $params = [
        'file' => UploadedFile::fake()->createWithContent(
            'invoices.pdf',
            Storage::get('./tests/Stubs/invoices.csv')
        ),
        'microsite_id' => Microsite::factory()->create()->id,
    ];

    $this->actingAs($user)->post(route('invoices.import'), $params)
        ->assertSessionHasErrors(['file']);
});
