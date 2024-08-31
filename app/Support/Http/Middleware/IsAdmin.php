<?php

namespace App\Support\Http\Middleware;

use App\Support\Definitions\Roles;
use App\Support\Definitions\Status;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Domain\Users\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole(ucwords(strtolower(Roles::GUEST->name))) ||
            $user->getRawOriginal('status') === Status::INACTIVE->value ||
            $user->getRawOriginal('role_id') === Roles::GUEST->value) {
            return redirect(route('home'));
        }

        return $next($request);
    }

}
