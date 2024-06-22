<?php

namespace App\Domain\Microsites\Models;

use Database\Factories\MicrositeFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Microsite extends Model
{
    use HasFactory;

    protected $table = 'microsites';

    protected $fillable = [
        'mirosite_type_id',
        'category_id',
        'name',
        'logo_path',
        'currency_id',
        'time_expire_pay',
        'status',
    ];

    protected static function newFactory(): Factory
    {
        return MicrositeFactory::new();
    }
}
