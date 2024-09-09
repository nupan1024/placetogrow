<?php

use App\Support\Http\Middleware\HandleInertiaRequests;
use App\Support\Http\Middleware\SwitchLanguage;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix('api')
                ->middleware('api')
                ->name('api')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
        $middleware->web(append: [
            SwitchLanguage::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class
        ]);
    })
    ->withExceptions(function () {
        //
    })->create();
