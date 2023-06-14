<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\PaymentGateways\Contracts\PaymentGatewayContract;
use App\PaymentGateways\Contracts\PaymentGatewayFactory;
use Closure;
use Illuminate\Http\Request;

class SetPaymentGatewayCredentials
{
    private $paymentGatewayFactory;

    public function __construct(PaymentGatewayFactory $paymentGatewayFactory)
    {
        $this->paymentGatewayFactory = $paymentGatewayFactory;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //Set credentials for payment gateway
        $profile=User::find(request()->route('profile_id'));
        $this->paymentGatewayFactory->paymentGateway(request()->route('gateway'))->setCredentials($profile);
        return $next($request);
    }
}
