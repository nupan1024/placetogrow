<?php

namespace App\Domain\SubscriptionUser\Models;

use App\Domain\Payments\Models\Payment;
use App\Domain\Subscriptions\Models\Subscription;
use App\Domain\Users\Models\User;
use Database\Factories\SubscriptionUserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionUser extends Model
{
    use HasFactory;

    protected $table = 'subscription_user';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'status',
        'token',
        'payment_id',
    ];

    protected static function newFactory(): Factory
    {
        return SubscriptionUserFactory::new();
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }
}
