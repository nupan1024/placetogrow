<?php

namespace App\Support\Definitions;

enum PaymentMethods: string
{
    case PLACE_TO_PAY = 'place_to_pay';

    public static function toArray(): array
    {
        $payments = self::cases();
        $array = [];

        foreach ($payments as $payment) {
            $array[strtolower($payment->name)] = $payment->value;
        }

        return $array;
    }
}
