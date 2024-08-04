<?php

namespace App\Domain\Invoices\Actions;

use App\Domain\Invoices\Models\Invoice;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;

class ListInvoicesByUser implements Action
{
    public static function execute(array $params): LengthAwarePaginator
    {
        return Invoice::select(
            'id',
            'code',
            'microsite_id',
            'user_id',
            'value',
            'description',
            'status',
        )->with(['user:id,name','microsite:id,name'])
            ->where('user_id', $params['user_id'])
            ->when($params['filter'], function ($query, $filter) {
                return $query->where(function ($query) use ($filter) {
                    $query->where('value', 'like', '%'.$filter.'%')
                        ->orWhere('description', 'like', '%'.$filter.'%')
                        ->orWhere('code', 'like', '%'.$filter.'%')
                        ->orWhere('status', 'like', '%'.$filter.'%')
                        ->orWhereHas('user', function ($query) use ($filter) {
                            $query->where('name', 'like', '%'.$filter.'%');
                        })
                        ->orWhereHas('microsite', function ($query) use ($filter) {
                            $query->where('name', 'like', '%'.$filter.'%');
                        });
                });
            })->latest('id')->paginate(10);
    }

}
