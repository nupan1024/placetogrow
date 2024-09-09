<?php

namespace App\Support\Definitions;

enum CurrenciesTypes: string
{
    case USD = 'USD';
    case COP = 'COP';
    public static function toArray(): array
    {
        $currencies = self::cases();
        $array = [];

        foreach ($currencies as $currency) {
            $array[] = [
                'name' => $currency->name,
            ];
        }

        return $array;
    }
}
