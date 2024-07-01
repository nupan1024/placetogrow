<?php

namespace App\Domain\Currencies\Models;

use Database\Factories\CurrencyFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currencies';

    protected $fillable = [
        'name',
        'status',
    ];

    protected static function newFactory(): Factory
    {
        return CurrencyFactory::new();
    }
}
