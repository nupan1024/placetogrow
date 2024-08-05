<?php

namespace App\Domain\Fields\Models;

use Database\Factories\FieldFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $table = 'fields';

    protected $casts = [
        'attributes' => 'array',
    ];

    protected $fillable = [
        'name',
        'type',
        'attributes',
        'label',
    ];

    protected static function newFactory(): Factory
    {
        return FieldFactory::new();
    }

}
