<?php

namespace App\Domain\Settings\Models;

use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];
    protected static function newFactory(): Factory
    {
        return SettingFactory::new();
    }
}
