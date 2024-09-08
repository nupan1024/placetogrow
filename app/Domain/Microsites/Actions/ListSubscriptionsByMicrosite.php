<?php

namespace App\Domain\Microsites\Actions;

use App\Domain\Subscriptions\Models\Subscription;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;

class ListSubscriptionsByMicrosite implements Action
{
    public static function execute(array $params = [], $model = null): LengthAwarePaginator
    {
        return Subscription::select(
            'subscriptions.id',
            'subscriptions.name',
            'subscriptions.amount',
            'subscriptions.description',
            'microsites.name as microsite',
            'currencies.name as currency',
            'subscriptions.time_expire',
            'subscriptions.billing_frequency',
            'subscriptions.status',
        )
               ->join('microsites', 'subscriptions.microsite_id', '=', 'microsites.id')
               ->join('currencies', 'subscriptions.currency_id', '=', 'currencies.id')
               ->where('subscriptions.microsite_id', $model->id)
               ->when($params['filter'], function ($query, $filter) {
                   return $query->where(function ($query) use ($filter) {
                       $query->where('subscriptions.name', 'like', '%'.$filter.'%')
                           ->orWhere('subscriptions.amount', 'like', '%'.$filter.'%')
                           ->orWhere('subscriptions.description', 'like', '%'.$filter.'%')
                           ->orWhere('subscriptions.time_expire', 'like', '%'.$filter.'%')
                           ->orWhere('subscriptions.billing_frequency', 'like', '%'.$filter.'%')
                           ->orWhere('subscriptions.status', 'like', '%'.$filter.'%')
                           ->orWhere('currencies.name', 'like', '%'.$filter.'%')
                           ->orWhere('microsites.name', 'like', '%'.$filter.'%');
                   });
               })->latest('subscriptions.id')->paginate(10);
    }

}
