<?php

namespace App\Support\Definitions;

enum StatusInvoices: int
{
    case PENDING = 0;
    case PAID = 1;
    case EXPIRED = 2;

    public static function asOptions(): array
    {
        $status = self::cases();
        $array = [];

        foreach ($status as $state) {
            $array[] = [
                'name' => ucfirst(strtolower($state->name)),
                'id' => $state->value,
            ];
        }

        return $array;
    }
}
