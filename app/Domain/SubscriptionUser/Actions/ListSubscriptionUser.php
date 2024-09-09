<?php

namespace App\Domain\SubscriptionUser\Actions;

use App\Domain\SubscriptionUser\Models\SubscriptionUser;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;

class ListSubscriptionUser implements Action
{
    public static function execute(array $params = [], $model = null): LengthAwarePaginator
    {
        return SubscriptionUser::select(
            'subscription_user.id',
            'subscription_user.status',
            'subscription_user.payment_id',
            'users.name as name',
            'subscriptions.id as subscription_id',
            'subscriptions.description as description',
            'subscriptions.amount as amount',
            'subscriptions.billing_frequency as frequency',
            'microsites.name as microsite_name',
        )
            ->join('subscriptions', 'subscription_user.subscription_id', '=', 'subscriptions.id')
            ->join('users', 'subscription_user.user_id', '=', 'users.id')
            ->leftJoin('microsites', 'subscriptions.microsite_id', '=', 'microsites.id')
            ->when($params['filter'], function ($query, $filter) {
                return $query->where(function ($query) use ($filter) {
                    $query->where('subscriptions.description', 'like', '%'.$filter.'%')
                        ->orWhere('subscriptions.amount', 'like', '%'.$filter.'%')
                        ->orWhere('users.name', 'like', '%'.$filter.'%')
                        ->orWhere('subscription_user.status', 'like', '%'.$filter.'%')
                        ->orWhere('subscriptions.billing_frequency', 'like', '%'.$filter.'%');
                });
            })->latest('id')->paginate(10);
    }

}
