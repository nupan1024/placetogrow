<?php

namespace App\Support\Definitions;

enum BillingFrequency: int
{
    case MONTH = 30;
    case YEAR = 365;
    case SEMESTER = 182;
    case QUARTER = 91;
    case WEEK = 7;
    case DAY = 1;
    public static function toArray(): array
    {
        $frequencies = self::cases();
        $array = [];

        foreach ($frequencies as $frequency) {
            $array[] = [
                'id' => $frequency->value,
                'name' => $frequency->name,
            ];
        }

        return $array;
    }
}
