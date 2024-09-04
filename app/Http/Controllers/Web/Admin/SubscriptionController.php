<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Subscriptions\Actions\CreateSubscription;
use App\Domain\Subscriptions\Actions\DeleteSubscription;
use App\Domain\Subscriptions\Actions\UpdateSubscription;
use App\Domain\Subscriptions\Models\Subscription;
use App\Domain\Subscriptions\ViewModels\CreateViewSubscriptions;
use App\Domain\Subscriptions\ViewModels\EditViewSubscriptions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Subscription\CreateSubscriptionRequest;
use App\Http\Requests\Admin\Subscription\UpdateSubscriptionRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Subscriptions/List');
    }

    public function create(): Response
    {
        return Inertia::render(
            'Admin/Subscriptions/Create',
            app(CreateViewSubscriptions::class)
        );
    }

    public function store(CreateSubscriptionRequest $request): RedirectResponse
    {
        CreateSubscription::execute($request->validated());

        return redirect()->route('subscriptions')->with([
            'message' => __('subscriptions.success_create'),
            'type' => 'success',
        ]);
    }

    public function edit(Subscription $subscription): Response
    {
        return Inertia::render(
            'Admin/Subscriptions/Edit',
            new EditViewSubscriptions($subscription)
        );
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription): RedirectResponse
    {
        UpdateSubscription::execute($request->validated(), $subscription);

        return redirect()->route('subscriptions')->with([
            'message' => __('subscriptions.success_update'),
            'type' => 'success',
        ]);
    }

    public function delete(Subscription $subscription): RedirectResponse
    {
        if(!DeleteSubscription::execute([], $subscription)) {
            return redirect()->route('subscriptions')->with([
                'message' => 'error',
                'type' => 'error',
            ]);
        }

        return redirect()->route('subscriptions')->with([
            'message' => __('subscriptions.success_delete'),
            'type' => 'success',
        ]);
    }

}
