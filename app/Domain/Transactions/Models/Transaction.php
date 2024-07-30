<?php

namespace App\Domain\Transactions\Models;

use App\Domain\Microsites\Models\Microsite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class, 'microsite_id', 'id');
    }

}
