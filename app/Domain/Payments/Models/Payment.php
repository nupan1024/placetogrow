<?php

namespace App\Domain\Payments\Models;

use App\Domain\Transactions\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'request_id',
        'process_url',
        'payment_type',
        'status',
        'transaction_id',

    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
