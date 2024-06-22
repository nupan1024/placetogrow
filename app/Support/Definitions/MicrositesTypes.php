<?php

namespace App\Support\Definitions;

enum MicrositesTypes: int
{
    case INVOICE = 1;
    case SUBSCRIPTIONS = 2;
    case DONATIONS = 3;

    public static function toArray(): array
    {
        $cases = self::cases();
        $array = [];

        foreach ($cases as $c) {
            $array[] = [
                'id' => $c->value,
                'name' => strtolower($c->name),
                'status' => Status::ACTIVE->value,
            ];
        }

        return $array;
    }
}
