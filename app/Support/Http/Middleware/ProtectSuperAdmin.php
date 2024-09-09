<?php

namespace App\Support\Http\Middleware;

use App\Support\Definitions\Roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user->name === ucwords(strtolower(str_replace(
            '_',
            ' ',
            Roles::SUPER_ADMIN->name
        )))) {
            return redirect(route('home'));
        }

        return $next($request);
    }

}
