<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPaymentGatewayRequest;
use App\PaymentGateways\Contracts\PaymentGatewayFactory;
use App\PaymentGateways\Stripe;


class PaymentGatewayController extends Controller
{
    /**
     * @param Stripe $gateway
     * @param AddPaymentGatewayRequest $request
     * @return mixed
     * Add or modify payment gateway details
     */
    public function add(PaymentGatewayFactory $paymentGatewayFactory, AddPaymentGatewayRequest $request)
    {
        $paymentGatewayFactory->paymentGateway(request()->route('gateway'))->addOrEditPaymentGateway($request);
        $this->setSuccessNotification("Successfully Updated Payment Gateway Details");
        return redirect('/payment_gateway/edit');
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * render payment gateway details page
     */
    public function edit()
    {
        return view('edit', auth()->user()->stripeGateway == null ? [] : auth()->user()->stripeGateway->toArray());
    }

}
