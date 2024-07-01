<?php

use App\Support\Definitions\MicrositesTypes;

return [
    'forms' => [
        MicrositesTypes::DONATIONS->value => 'Microsites/Forms/Donation',
        MicrositesTypes::INVOICE->value => 'Microsites/Forms/Invoice',
        MicrositesTypes::SUBSCRIPTIONS->value => 'Microsites/Forms/Suscription',
    ],
];
