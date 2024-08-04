<?php

namespace App\Domain\Invoices\Actions;

use App\Domain\Invoices\Models\Invoice;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;

class ListInvoices implements Action
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
            ->when($params['filter'], function ($query, $filter) {
                return $query->where(function ($query) use ($filter) {
                    $query->where('value', 'like', '%'.$filter.'%')
                        ->orWhere('description', 'like', '%'.$filter.'%')
                        ->orWhere('status', 'like', '%'.$filter.'%')
                        ->orWhere('attributes', 'like', '%'.$filter.'%');
                });
            })->latest('id')->paginate(10);
    }

}
