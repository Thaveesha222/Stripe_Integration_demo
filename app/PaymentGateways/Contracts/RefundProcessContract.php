<?php


namespace App\PaymentGateways\Contracts;


interface RefundProcessContract
{

    /**
     * Initialize Refund Process for payment
     * @param integer $donation_id
     * @param integer $user_id
     * @return mixed
     */
    public function refund($donation_id, $user_id);

    /**
     * Invoke after webhook for refund success is recieved
     * @param $payment_identifier_id
     * @return mixed
     */
    public function refundSuccessful($payment_identifier_id);
}
