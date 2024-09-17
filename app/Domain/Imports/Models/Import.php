<?php

namespace App\Domain\Imports\Models;

use App\Domain\Users\Models\User;
use App\Support\Definitions\ImportStatus;
use Database\Factories\ImportsFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Import extends Model
{
    use HasFactory;

    protected $table = 'imports';


    public const DISK = 'imports';

    protected $fillable = [
        'path',
        'file_name',
        'status',
        'errors',
    ];

    protected static function newFactory(): Factory
    {
        return ImportsFactory::new();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ImportStatus::class,
            'errors' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullPath(): string
    {
        return Storage::disk(self::DISK)->path($this->path);
    }
}
