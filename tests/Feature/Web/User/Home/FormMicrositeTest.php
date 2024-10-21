<?php

use App\Domain\Microsites\Models\Microsite;
use App\Domain\Microsites\Models\MicrositeType;
use App\Support\Definitions\MicrositesTypes;
use Inertia\Testing\AssertableInertia as Assert;

test('validate form microsite when is invoice', function () {
    MicrositeType::factory()->create([
        'id' => MicrositesTypes::INVOICE->value,
    ]);
    $microsite = Microsite::factory()->create([
        'microsites_type_id' => MicrositesTypes::INVOICE->value,
    ]);

    $response = $this->get('/form/microsite/' . $microsite->id);

    $response->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page) => $page
        ->component(config('microsites.forms')[$microsite->microsites_type_id])
        ->has('microsite')
        ->where('microsite.id', $microsite->id)
        ->has('fields')
        ->has('documents')
        ->has('invoices')
    );
});


test('validate form microsite when is donation', function () {
    MicrositeType::factory()->create([
        'id' => MicrositesTypes::DONATIONS->value,
    ]);
    $microsite = Microsite::factory()->create([
        'microsites_type_id' => MicrositesTypes::DONATIONS->value,
    ]);

    $response = $this->get('/form/microsite/' . $microsite->id);

    $response->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page) => $page
            ->component(config('microsites.forms')[$microsite->microsites_type_id])
            ->has('microsite')
            ->where('microsite.id', $microsite->id)
            ->has('fields')
            ->has('documents')
    );
});

test('validate form microsite when is subscription', function () {
    MicrositeType::factory()->create([
        'id' => MicrositesTypes::SUBSCRIPTIONS->value,
    ]);
    $microsite = Microsite::factory()->create([
        'microsites_type_id' => MicrositesTypes::SUBSCRIPTIONS->value,
    ]);

    $response = $this->get('/form/microsite/' . $microsite->id);

    $response->assertStatus(200);

    $response->assertInertia(
        fn (Assert $page) => $page
            ->component(config('microsites.forms')[$microsite->microsites_type_id])
            ->has('microsite')
            ->where('microsite.id', $microsite->id)
            ->has('fields')
            ->has('documents')
            ->has('subscriptions')
    );
});
