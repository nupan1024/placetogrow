<?php

namespace App\Domain\Payments\Models;

use App\Domain\Microsites\Models\Microsite;
use App\Domain\Subscriptions\Models\Subscription;
use App\Domain\SubscriptionUser\Models\SubscriptionUser;
use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'type_document',
        'num_document',
        'user_id',
        'microsite_id',
        'subscription_id',
        'invoice_id',

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

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }

    public function subscriptionUser(): HasOne
    {
        return $this->hasOne(SubscriptionUser::class, 'payment_id', 'id');
    }
}
