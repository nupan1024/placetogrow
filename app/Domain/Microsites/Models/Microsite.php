<?php

namespace App\Domain\Microsites\Models;

use App\Domain\Categories\Models\Category;
use App\Support\Definitions\Status;
use Database\Factories\MicrositeFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Microsite extends Model
{
    use HasFactory;

    protected $table = 'microsites';

    protected $fillable = [
        'microsites_type_id',
        'category_id',
        'name',
        'logo_path',
        'currency_id',
        'date_expire_pay',
        'status',
        'description',
    ];

    protected static function newFactory(): Factory
    {
        return MicrositeFactory::new();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(MicrositeType::class, 'microsites_type_id', 'id');
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
