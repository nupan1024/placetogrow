<?php

namespace App\Domain\Transactions\Actions;

use App\Domain\Transactions\Models\Transaction;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateTransaction implements Action
{
    public static function execute(array $params): Transaction|bool
    {
        try {
            $transaction = new Transaction();
            $transaction->name = $params['name'];
            $transaction->email = $params['email'];
            $transaction->value = $params['value'];
            $transaction->fields = $params['fields'] ?? [];
            $transaction->type_document = $params['type_document'];
            $transaction->num_document = $params['num_document'];
            $transaction->user_id = (!is_null(Auth::user())) ? Auth::user()->id : null;
            $transaction->microsite_id = $params['microsite_id'];
            $transaction->code = 'TRANSACTION_'. $params['microsite_id'] . '_' . now()->format('YmdHis');
            $transaction->save();
            return $transaction;
        } catch (\Exception $e) {
            Log::channel('Transaction')->error('Error creating transaction: '.$e->getMessage());

            return false;
        }
    }
}
