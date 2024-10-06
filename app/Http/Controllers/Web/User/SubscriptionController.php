<?php

namespace App\Http\Controllers\Web\User;

use App\Domain\Subscriptions\Models\Subscription;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Subscription/List');
    }

    public function delete(Subscription $subscription): Response
    {
        //$subscription->delete();

        return Inertia::render('Subscription/List');

    }
}
