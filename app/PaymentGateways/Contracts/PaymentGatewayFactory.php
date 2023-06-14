<?php


namespace App\PaymentGateways\Contracts;


interface PaymentGatewayFactory
{
    /**
     * Get a payment gateway instance by name.
     *
     * @param  string|null  $name
     * @return \App\PaymentGateways\Contracts\PaymentGatewayContract
     */
    public function paymentGateway($name = null);
}
