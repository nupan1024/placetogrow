<?php

use App\Domain\Invoices\Actions\CreateInvoice;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use Carbon\Carbon;

test('create invoice', function () {
    $microsite = Microsite::factory()->create();
    $user = User::factory()->create();
    $params = [
        'microsite_id' => $microsite->id,
        'user_id' => $user->id,
        'value' => '6000',
        'description' => fake()->paragraph(),
        'date_expire_pay' => fake()->date(Carbon::now()->addDays(3)->format('Y-m-d')),
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
