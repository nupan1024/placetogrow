<?php

namespace App\Domain\Invoices\Actions;

use App\Domain\Invoices\Models\Invoice;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;

class ListInvoices implements Action
{
    public static function execute(array $params = [], $model = null): LengthAwarePaginator
    {
        return Invoice::select(
            'invoices.id',
            'invoices.code',
            'microsites.name as microsite',
            'users.name as user',
            'invoices.value',
            'invoices.description',
            'invoices.status',
        )
            ->join('microsites', 'invoices.microsite_id', '=', 'microsites.id')
            ->join('users', 'invoices.user_id', '=', 'users.id')
            ->when($params['filter'], function ($query, $filter) {
                return $query->where(function ($query) use ($filter) {
                    $query->where('invoices.value', 'like', '%'.$filter.'%')
                        ->orWhere('invoices.description', 'like', '%'.$filter.'%')
                        ->orWhere('invoices.code', 'like', '%'.$filter.'%')
                        ->orWhere('invoices.status', 'like', '%'.$filter.'%')
                        ->orWhere('users.name', 'like', '%'.$filter.'%')
                        ->orWhere('microsites.name', 'like', '%'.$filter.'%');
                });
            })->latest('id')->paginate(10);
    }

}
