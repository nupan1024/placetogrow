<?php

namespace App\Support\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface Action
{
    public static function execute(array $params): bool|int|Model|LengthAwarePaginator;
}
