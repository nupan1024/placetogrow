<?php

namespace App\Domain\Payments\Actions;

use App\Domain\Payments\Models\Payment;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;

class ListPaymentsByUser implements Action
{
    public static function execute(array $params = [], $model = null): LengthAwarePaginator
    {
        return Payment::select(
            'payments.id',
            'payments.request_id',
            'payments.value',
            'payments.status',
            'payments.payment_type',
            'microsites.id as microsite_id',
            'microsites_types.name as microsite_type_name'
        )
            ->join('microsites', 'payments.microsite_id', '=', 'microsites.id')
            ->leftJoin('microsites_types', 'microsites.microsites_type_id', '=', 'microsites_types.id')
            ->where('user_id', $params['user_id'])
            ->when($params['filter'], function ($query, $filter) {
                return $query->where(function ($query) use ($filter) {
                    $query->where('payments.request_id', 'like', '%'.$filter.'%')
                        ->orWhere('payments.status', 'like', '%'.$filter.'%')
                        ->orWhere('payments.payment_type', 'like', '%'.$filter.'%')
                        ->orWhere('microsites_types.name', 'like', '%'.$filter.'%');
                });
            })->latest('id')->paginate(10);
    }

}
