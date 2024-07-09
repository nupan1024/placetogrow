<?php

namespace App\Support\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ClearMicrositeCache
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Cache::forget('admin_microsites_page_1_filter_ ');
        Cache::forget('microsites_page_1_filter_ ');

        return $next($request);
    }
}
