<?php

namespace App\Domain\Users\Models;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role as RoleSpatie;

class Role extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @throws \Exception
     */
    public function value(): RoleSpatie|bool
    {
        try {
            return RoleSpatie::findById($this->id);
        } catch (\Exception $e) {
            Log::channel('Users')->error('Failed to login: '.$e->getMessage());
            return false;
        }
    }

    protected static function newFactory(): Factory
    {
        return RoleFactory::new();
    }
}
