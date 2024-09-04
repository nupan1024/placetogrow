<?php

namespace App\Domain\Subscriptions\Actions;

use App\Domain\Subscriptions\Models\Subscription;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ListSubscription implements Action
{
    public static function execute(array $params = [], $model = null): LengthAwarePaginator
    {
        $cacheKey = 'admin_subscriptions_page_'.$params['page'].'_filter_'.$params['filter'];

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($params) {
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
                })->latest('id')->paginate(10);
        });

    }

}
