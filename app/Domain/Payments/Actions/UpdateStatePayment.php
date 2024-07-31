<?php

namespace App\Domain\Payments\Actions;

use App\Support\Actions\Action;

class UpdateStatePayment implements Action
{
    public static function execute(array $params): bool
    {
        $params['payment']->status = $params['status'];
        return $params['payment']->save();
    }

}
