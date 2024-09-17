<?php

namespace App\Support\Definitions;

use App\Concerns\EnumToArray;

enum ImportStatus: string
{
    use EnumToArray;

    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';

    public function text(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
        };
    }
}
