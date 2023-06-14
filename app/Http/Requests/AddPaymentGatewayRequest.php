<?php

namespace App\Http\Requests;

use App\PaymentGateways\Stripe;
use Illuminate\Foundation\Http\FormRequest;

class AddPaymentGatewayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (request()->route('gateway') == Stripe::$name) {
            return [
                'secret' => 'required|unique:stripe_payment_gateways,secret,'.auth()->user()->id.',user_id',
                'key' => 'required|unique:stripe_payment_gateways,key,'.auth()->user()->id.',user_id',
            ];
        }
    }
}
