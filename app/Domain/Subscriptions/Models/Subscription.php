<?php

namespace App\Domain\Subscriptions\Models;

use App\Domain\Microsites\Models\Microsite;
use Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $fillable = [
        'microsite_id',
        'name',
        'amount',
        'description',
        'currency_id',
        'time_expire',
        'billing_frequency',
        'status',
    ];

    protected static function newFactory(): Factory
    {
        return SubscriptionFactory::new();
    }
    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class, 'microsite_id', 'id');
    }
}
