<?php

use App\Domain\Invoices\Actions\CreateInvoice;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;

test('create invoice', function () {
    $microsite = Microsite::factory()->create();
    $user = User::factory()->create();
    $params = [
        'microsite_id' => $microsite->id,
        'user_id' => $user->id,
        'value' => '6000',
        'description' => fake()->paragraph(),
    ];

    expect(CreateInvoice::execute($params))->toBeTrue();
});

test('generate exception invoice creation', function () {
    $microsite = Microsite::factory()->create();
    $params = [
        'microsite_id' => $microsite->id,
        'user_id' => "",
        'value' => '6000',
        'description' => fake()->paragraph(),
    ];

    expect(CreateInvoice::execute($params))->toBeFalse();
});
