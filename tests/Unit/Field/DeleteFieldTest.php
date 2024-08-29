<?php

use App\Domain\Categories\Models\Category;
use App\Domain\Currencies\Models\Currency;
use App\Domain\Fields\Actions\DeleteField;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Microsites\Models\MicrositeType;
use App\Support\Definitions\MicrositesTypes;
use App\Support\Definitions\Status;
use Database\Factories\FieldFactory;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

test('delete field', function () {
    $field = FieldFactory::new()->create();

    expect(DeleteField::execute(['field' => $field]))->toBeTrue();
});

test('microsite with this field', function () {
    $field = FieldFactory::new()->create();

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
    Microsite::factory()->create([
        'microsites_type_id' => MicrositesTypes::SUBSCRIPTIONS->value,
        'category_id' => $category->id,
        'name' => 'Test microsite',
        'logo_path' => UploadedFile::fake()
            ->image('microsite_image.png', 640, 480),
        'description' => 'Test description',
        'currency_id' => $currency->id,
        'status' => Status::ACTIVE->value,
        'fields' => [$field->name]
    ]);


    expect(DeleteField::execute(['field' => $field]))->toBeFalse();
});

test('generate exception delete field', function () {
    expect(DeleteField::execute(['field' => ""]))->toBeFalse();
});
