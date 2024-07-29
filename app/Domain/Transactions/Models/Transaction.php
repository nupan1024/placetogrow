<?php

namespace App\Domain\Transactions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'name',
        'email',
        'value',
        'data',
        'type_document',
        'num_document',
        'microsite_id',
        'user_id',
        'code',

    ];

}
