<?php

namespace App\Support\Http\Middleware;

use App\Support\Definitions\Roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $roles = [Roles::SUPER_ADMIN->value, Roles::GUEST->value];
        if (in_array($request->role->id, $roles)) {
            return redirect(route('home'));
        }

        return $next($request);
    }

}
