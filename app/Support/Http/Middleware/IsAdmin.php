<?php

namespace App\Support\Http\Middleware;

use App\Support\Definitions\Roles;
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
        $user = Auth::user();
        if ($user->role->value() === Roles::GUEST) {
            return redirect(route('home'));
        }

        return $next($request);
    }
}
