<?php

namespace App\Http\Controllers\Web\User;

use App\Domain\SubscriptionUser\Actions\DeleteSubscriptionUser;
use App\Domain\SubscriptionUser\Models\SubscriptionUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Subscription/List');
    }

    public function delete(SubscriptionUser $subscriptionUser): RedirectResponse
    {
        if (!DeleteSubscriptionUser::execute([], $subscriptionUser)) {
            return redirect()->route('user.subscriptions.list')->with([
                'message' => __('subscriptions.error_delete'),
                'type' => 'error',
            ]);
        }

        return redirect()->route('user.subscriptions.list')->with([
            'message' => __('subscriptions.success_delete'),
            'type' => 'success',
        ]);
    }
}
