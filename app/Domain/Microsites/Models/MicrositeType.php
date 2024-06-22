<?php

namespace App\Domain\Microsites\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MicrositeType extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'microsites_types';

    protected $fillable = [
        'name',
        'status'
    ];
}
