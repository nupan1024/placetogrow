<?php

namespace App\Domain\Microsites\Models;

use App\Domain\Categories\Models\Category;
use Database\Factories\MicrositeFactory;
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
}
