<?php


namespace App\PaymentGateways\Contracts;


interface ACHPaymentContract
{
    /**
     * Initialize ACH payment using gateway
     * @param numeric $amount
     * @return mixed
     */
    public function achPayment($amount);

    /**
     * Invoked after webhook for payment success is recieved
     * @param string $payment_identifier_id
     * @param string $payment_confirmation_id
     * @return mixed
     */
    public function paymentSuccess($payment_identifier_id, $payment_confirmation_id);
}
