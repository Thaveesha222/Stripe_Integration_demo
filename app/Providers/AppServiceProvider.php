<?php

namespace App\Providers;

use App\Models\StripePaymentGateway;
use App\PaymentGateways\Contracts\PaymentGatewayContract;
use App\PaymentGateways\Contracts\PaymentGatewayFactory;
use App\PaymentGateways\Stripe;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PaymentGatewayFactory::class, function ($app) {
            return new \App\PaymentGateways\PaymentGatewayManager($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
