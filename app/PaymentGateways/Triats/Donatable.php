<?php


namespace App\PaymentGateways\Triats;

use App\Models\Donation;

trait Donatable
{
    public function createIncompleteDonation($from,$to,$amount,$gateway)
    {
        return Donation::create([
            'from'=>$from,
            'to'=>$to,
            'amount'=>$amount,
            'payment_gateway'=>$gateway,
        ]);
    }

    public function completeDonation($id)
    {
        $donation=Donation::find($id);
        $donation->completed=true;
        $donation->save();
    }

    public function refundDonation($id)
    {
        $donation=Donation::find($id);
        $donation->refunded=true;
        $donation->save();
    }
}
