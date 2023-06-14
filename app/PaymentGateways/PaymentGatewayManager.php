<?php


namespace App\PaymentGateways;


use App\PaymentGateways\Contracts\PaymentGatewayFactory as FactoryContract;
use Illuminate\Support\Arr;

class PaymentGatewayManager implements FactoryContract
{
    private $app;

    /**
     * The array of resolved payment gateways.
     *
     * @var array
     */
    protected $paymentGateways = [];

    /**
     * Create a new Payment Gateway manager instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get a payment gateway instance by name.
     *
     * @param string|null $name
     * @return \App\PaymentGateways\Contracts\PaymentGatewayContract
     */
    public function paymentGateway($name = null)
    {
        $service = Arr::get($this->paymentGateways, $name);
        // No need to create the service every time
        if ($service) {
            return $service;
        }
        $createMethod = 'create' . ucfirst($name) . 'Service';
        if (!method_exists($this, $createMethod)) {
            throw new \Exception("Payment Gateway $name is not supported");
        }
        $service = $this->{$createMethod}();
        $this->paymentGateways[$name] = $service;
        return $service;
    }

    private function createStripeService(): Stripe
    {
        return new Stripe();
    }
}
