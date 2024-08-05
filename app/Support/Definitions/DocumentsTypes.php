<?php

namespace App\Support\Definitions;

enum DocumentsTypes: int
{
    case CC = 1;
    case CE = 2;
    public static function toArray(): array
    {
        $documents = self::cases();
        $array = [];

        foreach ($documents as $document) {
            $array[] = [
                'id' => $document->value,
                'name' => $document->name,
            ];
        }

        return $array;
    }
}
