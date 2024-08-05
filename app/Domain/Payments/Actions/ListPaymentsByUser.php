<?php

namespace App\Domain\Payments\Actions;

use App\Domain\Payments\Models\Payment;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;

class ListPaymentsByUser implements Action
{
    public static function execute(array $params): LengthAwarePaginator
    {
        return Payment::select(
            'id',
            'request_id',
            'value',
            'status',
            'payment_type',
            'microsite_id',
        )->with('microsite.type')
            ->where('user_id', $params['user_id'])
            ->when($params['filter'], function ($query, $filter) {
                return $query->where(function ($query) use ($filter) {
                    $query->where('request_id', 'like', '%'.$filter.'%')
                        ->orWhere('status', 'like', '%'.$filter.'%')
                        ->orWhere('payment_type', 'like', '%'.$filter.'%');
                });
            })->latest('id')->paginate(10);
    }

}
