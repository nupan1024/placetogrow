<?php

namespace App\Support\Providers;

use App\Contracts\PaymentGateway as PaymentGatewayContract;
use App\Contracts\PaymentService as PaymentServiceContract;
use App\Support\Definitions\PaymentGateway;
use App\Support\Services\Payments\Gateways\PlaceToPayService;
use App\Support\Services\Payments\PaymentService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentServiceContract::class, function (Application $app, array $data) {
            $gateway = $data['gateway'] ?? null;
            $payment = $data['payment'] ?? null;

            $gateway = $app->make(PaymentGatewayContract::class, ['gateway' => $gateway]);

            return new PaymentService($payment, $gateway);
        });

        $this->app->bind(PaymentGatewayContract::class, function (Application $app, array $data) {
            return match (PaymentGateway::from($data['gateway'] ?? null)) {
                PaymentGateway::PLACETOPAY => new PlaceToPayService(),
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
