<?php

use App\Domain\Categories\Models\Category;
use App\Domain\Currencies\Models\Currency;
use App\Domain\Microsites\Actions\UpdateMicrosite;
use App\Domain\Microsites\Models\Microsite;
use App\Support\Definitions\MicrositesTypes;
use App\Support\Definitions\Status;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

test('update microsite', function () {
    $microsite = Microsite::factory()->create();
    $params['fields'] = [
        'microsites_type_id' => $microsite->microsites_type_id,
        'category_id' => $microsite->category_id,
        'name' => 'Test microsite',
        'logo_path' => UploadedFile::fake()
            ->image('microsite_image.png', 640, 480),
        'description' => 'Test update description',
        'currency_id' => $microsite->currency_id,
        'status' => Status::ACTIVE->value,
    ];

    $params['id'] = $microsite->id;
    $this->assertTrue(UpdateMicrosite::execute($params));
});

test('generate exception', function () {
    $category = Category::factory()->create([
        'name' => Str::limit(fake()->unique()->name(), 10),
        'status' => Status::ACTIVE->value,
    ]);

    $currency = Currency::factory()->create([
        'name' => 'USD',
        'status' => Status::ACTIVE->value,
    ]);

    $params['fields'] = [
        'microsites_type_id' => MicrositesTypes::SUBSCRIPTIONS->value,
        'category_id' => $category->id,
        'name' => 'Test update microsite',
        'logo_path' => UploadedFile::fake()
            ->image('microsite_image.png', 640, 480),
        'description' => 'Test update description',
        'currency_id' => $currency->id,
        'status' => Status::ACTIVE->value,
    ];

    $this->assertFalse(UpdateMicrosite::execute($params));
});
