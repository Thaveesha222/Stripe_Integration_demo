<?php

namespace App\PaymentGateways\Contracts;

use App\Http\Requests\AddPaymentGatewayRequest;
use App\Models\User;


interface PaymentGatewayContract
{
    /**
     * Set the payment gateway credentials of the user to app config
     * @param User $user
     * @return mixed
     */
    public function setCredentials(User $user);

    /**
     * Add new payemnt gateway or edit existing payment gateway
     * @param AddPaymentGatewayRequest $request
     * @return mixed
     */
    public function addOrEditPaymentGateway(AddPaymentGatewayRequest $request);

    /**
     * Initialize card payment using gateway
     * @param numeric $amount
     * @return mixed
     */
    public function creditCardPayment($amount);


    /**
     * Invoked after webhook for payment success is recieved
     * @param string $payment_identifier_id
     * @param string $payment_confirmation_id
     * @return mixed
     */
    public function paymentSuccess($payment_identifier_id, $payment_confirmation_id);

    /**
     * Invoke for failed payments
     * @param $intent_id
     * @return string mixed
     */
    public function paymentFail($payment_identifier_id);

}
