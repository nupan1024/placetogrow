<?php

namespace App\Domain\Payments\Models;

use App\Domain\Microsites\Models\Microsite;
use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
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
    protected $casts = [
        'fields' => 'array',
    ];

    protected static function newFactory(): Factory
    {
        return PaymentFactory::new();
    }

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class, 'microsite_id', 'id');
    }
}
