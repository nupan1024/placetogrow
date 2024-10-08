<?php

use App\Domain\Currencies\Models\Currency;
use App\Domain\Microsites\Actions\UpdateMicrosite;
use App\Domain\Microsites\Models\Microsite;
use App\Support\Definitions\Status;
use Illuminate\Http\UploadedFile;

use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertFalse;

test('update microsite', function () {
    $microsite = Microsite::factory()->create();
    $params = [
        'category_id' => $microsite->category_id,
        'name' => 'Test microsite',
        'logo_path' => UploadedFile::fake()
            ->image('microsite_image.png', 640, 480),
        'description' => 'Test update description',
        'currency_id' => $microsite->currency_id,
        'status' => Status::ACTIVE->value,
    ];

    assertTrue(UpdateMicrosite::execute($params, $microsite));
});

test('generate exception', function () {
    $microsite = Microsite::factory()->create();

    $currency = Currency::factory()->create([
        'name' => 'USD',
    ]);
    $params = [
        'name' => 'Test update microsite',
        'logo_path' => UploadedFile::fake()
            ->image('microsite_image.png', 640, 480),
        'description' => 'Test update description',
        'currency_id' => $currency->id,
        'status' => Status::ACTIVE->value,
    ];

    assertFalse(UpdateMicrosite::execute($params, $microsite));
});
