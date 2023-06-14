<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;
    protected $fillable = [
        'from',
        'to',
        'amount',
        'payment_gateway',
        'completed',
        'refunded',
    ];
    public function donationFrom()
    {
        return $this->belongsTo(User::class,'from','id');
    }

    public function donationTo()
    {
        return $this->belongsTo(User::class,'to','id');
    }
}
