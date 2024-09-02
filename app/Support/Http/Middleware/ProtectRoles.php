<?php

namespace App\Support\Http\Middleware;

use App\Domain\Roles\Actions\GetRole;
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
        $guest = GetRole::execute(['name' => Roles::GUEST->name]);
        $admin = GetRole::execute(['name' => Roles::SUPER_ADMIN->name]);

        $roles = [$guest->id, $admin->id];

        if (in_array($request->role->id, $roles)) {
            return redirect(route('home'));
        }

        return $next($request);
    }

}
