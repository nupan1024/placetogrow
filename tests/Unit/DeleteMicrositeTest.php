<?php

use App\Domain\Categories\Models\Category;
use App\Domain\Currencies\Models\Currency;
use App\Domain\Microsites\Actions\DeleteMicrosite;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Microsites\Models\MicrositeType;
use App\Support\Definitions\MicrositesTypes;
use App\Support\Definitions\Status;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

test('delete microsite', function () {
    MicrositeType::factory()->create([
        'id' => MicrositesTypes::SUBSCRIPTIONS->value,
        'name' => MicrositesTypes::SUBSCRIPTIONS->name,
        'status' => Status::ACTIVE->value,
    ]);

    $category = Category::factory()->create([
        'name' => Str::limit(fake()->unique()->name(), 10),
        'status' => Status::ACTIVE->value,
    ]);

    $currency = Currency::factory()->create([
        'name' => 'USD',
        'status' => Status::ACTIVE->value,
    ]);

    $microsite = Microsite::factory()->create([
        'microsites_type_id' => MicrositesTypes::SUBSCRIPTIONS->value,
        'category_id' => $category->id,
        'name' => 'Test microsite',
        'logo_path' => UploadedFile::fake()
            ->image('microsite_image.png', 640, 480),
        'description' => 'Test description',
        'currency_id' => $currency->id,
        'status' => Status::ACTIVE->value,
    ]);

    $this->assertTrue(DeleteMicrosite::execute(['id' => $microsite->id]));
});


test('it not found microsite', function () {
    $this->assertFalse(DeleteMicrosite::execute(['id' => ""]));
});
