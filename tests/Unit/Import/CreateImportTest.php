<?php

use App\Domain\Imports\Actions\CreateImport;
use App\Domain\Imports\Models\Import;
use App\Domain\Users\Models\User;
use App\Support\Definitions\ImportStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

test('create import', function () {

    Storage::fake(Import::DISK);

    $params = [
        'file' => new UploadedFile('./tests/Stubs/invoices.csv', 'invoices.csv'),
        'status' => fake()->randomElement(ImportStatus::toArray()),
        'errors' => [],
    ];

    Auth::login(User::factory()->create());

    expect(CreateImport::execute($params))->toBeInstanceOf(Import::class);
});

test('generate exception import creation', function () {
    $params = [
        'path' => 'file.csv',
        'status' => fake()->randomElement(ImportStatus::toArray()),
        'errors' => [],
    ];

    expect(CreateImport::execute($params))->toBeFalse();
});
