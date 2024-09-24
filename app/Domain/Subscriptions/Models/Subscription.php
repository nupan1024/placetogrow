<?php

namespace App\Domain\Subscriptions\Models;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Definitions\Status;
use Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => match ($value) {
                Status::ACTIVE->value => ucfirst(strtolower(Status::ACTIVE->name)),
                Status::INACTIVE->value => ucfirst(strtolower(Status::INACTIVE->name)),
                default => null
            },
            set: static function ($value) {
                return $value ? Status::ACTIVE->value : Status::INACTIVE->value;
            }
        );
    }
}
