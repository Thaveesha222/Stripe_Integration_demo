<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationRequest;
use App\PaymentGateways\Contracts\PaymentGatewayFactory;

class DonationController extends Controller
{
    /**
     * @param PaymentGatewayFactory $paymentGatewayFactory
     * @param DonationRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     * @throws \Stripe\Exception\ApiErrorException
     * Make donation
     */
    public function makeDonation(PaymentGatewayFactory $paymentGatewayFactory, DonationRequest $request)
    {
        if ($request->payment_method == "cc") {
            return $paymentGatewayFactory->paymentGateway(request()->route('gateway'))
                ->creditCardPayment($request->amount);
        } else {
            return $paymentGatewayFactory->paymentGateway(request()->route('gateway'))
                ->achPayment($request->amount);
        }
    }

    /**
     * @param PaymentGatewayFactory $paymentGatewayFactory
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * Refund donation
     */
    public function refundDonation(PaymentGatewayFactory $paymentGatewayFactory)
    {
        $paymentGatewayFactory->paymentGateway(request()->route('gateway'))
            ->refund(request()->route('donation_id'), request()->route('profile_id'));
        $this->setSuccessNotification("Successfully refunded donation");
        return redirect(url()->previous());
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * show all donations
     */
    public function showDonations()
    {
        return view('donations')
            ->with('recieved', auth()->user()->donationsRecieved)
            ->with('given', auth()->user()->donationsGiven);
    }
}
