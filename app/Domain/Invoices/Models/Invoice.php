<?php

namespace App\Domain\Invoices\Models;

use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'microsite_id',
        'user_id',
        'value',
        'description',
        'status',
        'date_expire_pay',
    ];

    protected static function newFactory(): Factory
    {
        return InvoiceFactory::new();
    }
    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class, 'microsite_id', 'id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
