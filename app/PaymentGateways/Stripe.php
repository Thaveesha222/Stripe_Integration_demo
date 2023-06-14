<?php


namespace App\PaymentGateways;


use App\Http\Requests\AddPaymentGatewayRequest;
use App\Models\Donation;
use App\Models\StripePayment;
use App\Models\StripePaymentGateway;
use App\Models\User;
use App\PaymentGateways\Contracts\ACHPaymentContract;
use App\PaymentGateways\Contracts\PaymentGatewayContract;
use App\PaymentGateways\Contracts\RefundProcessContract;
use App\PaymentGateways\Triats\Donatable;
use Illuminate\Support\Facades\Config;

class Stripe implements PaymentGatewayContract, RefundProcessContract, ACHPaymentContract
{
    use Donatable;

    public static $name = "stripe";

    /**
     * @param User $user
     * @return mixed|void
     * @throws \Exception
     */
    public function setCredentials(User $user)
    {
        if ($user->stripeGateway != null) {
            Config::set('cashier.key', $user->stripeGateway->key);
            Config::set('cashier.secret', $user->stripeGateway->secret);
            \Stripe\Stripe::setApiKey($user->stripeGateway->secret);
        } else {
            throw new \Exception('Stripe Not Available for user');
        }

    }

    /**
     * @param AddPaymentGatewayRequest $request
     * @return mixed
     */
    public function addOrEditPaymentGateway(AddPaymentGatewayRequest $request)
    {
        return StripePaymentGateway::updateOrCreate(
            ['user_id' => auth()->user()->id],
            [
                'secret' => $request->secret,
                'key' => $request->key,
                'cc_enabled' => $request->has('cc_enabled'),
                'ach_enabled' => $request->has('ach_enabled'),
            ]);
    }

    /**
     * @param float|int|string $amount
     * @return mixed
     */
    public function creditCardPayment($amount)
    {
        $donation = $this->createIncompleteDonation(auth()->user()->id, request()->route('profile_id'), $amount, self::$name);
        $payment = auth()->user()->checkoutCharge($amount*100, 'Donation', 1, [], ['email' => auth()->user()->email]);
        StripePayment::create([
            'donations_id' => $donation->id,
            'payment_intent_id' => $payment->payment_intent,
        ]);
        return $payment;
    }


    /**
     * @param float|int|string $amount
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function achPayment($amount)
    {
        $donation = $this->createIncompleteDonation(auth()->user()->id, request()->route('profile_id'), $amount, self::$name);
        $customer = auth()->user()->createOrGetStripeCustomer();
        $object = (\Stripe\Checkout\Session::create([
            'payment_method_types' => ['us_bank_account'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Donation',
                    ],
                    'unit_amount' => $amount*100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'customer' => $customer->stripe_id,
            'success_url' => $sessionOptions['success_url'] ?? route('home') . '?checkout=success',
            'cancel_url' => $sessionOptions['cancel_url'] ?? route('home') . '?checkout=cancelled',
        ]));
        StripePayment::create([
            'donations_id' => $donation->id,
            'payment_intent_id' => $object->payment_intent
        ]);
        return redirect($object->url);
    }

    /**
     * @param string $payment_identifier_id
     * @param string $payment_confirmation_id
     * @return mixed|void
     */
    public function paymentSuccess($payment_identifier_id, $payment_confirmation_id)
    {
        //set donation as complete
        $stripePayment = StripePayment::where('payment_intent_id', $payment_identifier_id)->first();
        $donation = Donation::find($stripePayment->donations_id);
        $this->completeDonation($donation->id);

        //set stripe payment as complete
        $stripePayment->payment_successfull = true;
        $stripePayment->payment_id = $payment_confirmation_id;
        $stripePayment->save();
    }

    /**
     * @param $payment_identifier_id
     * @return string|void
     */
    public function paymentFail($payment_identifier_id)
    {
        $stripePayment = StripePayment::where('payment_intent_id', $payment_identifier_id)->delete();
        $donation = Donation::find($stripePayment->donations_id)->delete();
    }

    /**
     * @param int $donation_id
     * @param int $user_id
     * @return mixed|void
     */
    public function refund($donation_id, $user_id)
    {
        $payment = StripePayment::where('donations_id', $donation_id)->first();
        return User::find($user_id)->refund($payment->payment_intent_id);
    }

    /**
     * @param $payment_identifier_id
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function refundSuccessful($payment_identifier_id)
    {
        $donation_id = StripePayment::where('payment_intent_id', $payment_identifier_id)->first()->donations_id;
        $this->refundDonation($donation_id);
        return back();
    }
}
