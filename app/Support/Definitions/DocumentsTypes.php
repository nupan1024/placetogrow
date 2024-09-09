<?php

namespace App\Support\Definitions;

enum DocumentsTypes: string
{
    case CC = 'CC';
    case CE = 'CE';
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
