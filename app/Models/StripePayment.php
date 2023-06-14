<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripePayment extends Model
{
    protected $fillable = [
        'donations_id',
        'payment_id',
        'payment_intent_id',
        'payment_successfull'
    ];

    use HasFactory;



}
