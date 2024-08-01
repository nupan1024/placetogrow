<?php

namespace App\Domain\Payments\Actions;

use App\Domain\Payments\Models\Payment;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;

class ListPayments implements Action
{
    public static function execute(array $params): LengthAwarePaginator
    {
        return Payment::select(
            'request_id',
            'status',
            'payment_type',
            'transaction_id',
        )->with('transaction.microsite.type')
            ->when($params['filter'], function ($query, $filter) {
                return $query->where(function ($query) use ($filter) {
                    $query->where('request_id', 'like', '%'.$filter.'%')
                        ->orWhere('status', 'like', '%'.$filter.'%')
                        ->orWhere('payment_type', 'like', '%'.$filter.'%');
                });
            })->latest('id')->paginate(10);
    }

}
