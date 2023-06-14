<?php

namespace App\Listeners;

use App\PaymentGateways\Contracts\PaymentGatewayFactory;
use App\PaymentGateways\Stripe;
use Laravel\Cashier\Events\WebhookReceived;

class ChargeSuccessListener
{
    private $paymentGatewayFactory;
    public function __construct(PaymentGatewayFactory $paymentGatewayFactory)
    {
        $this->paymentGatewayFactory=$paymentGatewayFactory;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        if ($event->payload['type'] === 'charge.succeeded') {
            $intent_id=$event->payload["data"]["object"]["payment_intent"];
            $payment_id=$event->payload["data"]["object"]["id"];
            $this->paymentGatewayFactory->paymentGateway(Stripe::$name)->paymentSuccess($intent_id,$payment_id);
        }
        else if ($event->payload['type'] === 'charge.refunded')
        {
            $intent_id=$event->payload["data"]["object"]["payment_intent"];
            $this->paymentGatewayFactory->paymentGateway(Stripe::$name)->refundSuccessful($intent_id);
        }
    }
}
