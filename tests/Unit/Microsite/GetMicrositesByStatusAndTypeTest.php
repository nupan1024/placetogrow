<?php

use App\Domain\Microsites\Actions\GetMicrositesByStatusAndType;
use App\Domain\Microsites\Models\Microsite;
use App\Support\Definitions\MicrositesTypes;
use App\Support\Definitions\Status;

test('get microsites by status and type', function () {
    Microsite::factory()->create();
    expect(GetMicrositesByStatusAndType::execute([
        'status' => Status::ACTIVE->value,
        'type' => MicrositesTypes::INVOICE->value,
    ]))->toBeObject();
});
