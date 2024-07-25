<?php

namespace App\Support\Http\Middleware;

use App\Support\Definitions\Roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->role->id === Roles::SUPER_ADMIN->value) {
            return redirect(route('home'));
        }

        return $next($request);
    }

}
