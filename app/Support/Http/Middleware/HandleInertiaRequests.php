<?php

namespace App\Support\Http\Middleware;

use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /*
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
                'user_permissions' => $request->user() ? $request->user()->getAllPermissions()->pluck('name')->toArray() : [],
                'permissions' => Permissions::getPermissions() ?? [],
                'roles' => Roles::getRoles() ?? [],
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'type' => fn () => $request->session()->get('type'),
            ],
            '$t' => [
                'microsites' => __('microsites'),
                'categories' => __('categories'),
                'labels' => __('labels'),
                'auth' => __('auth'),
                'roles' => __('roles'),
                'users' => __('users'),
            ],
        ]);
    }
}
