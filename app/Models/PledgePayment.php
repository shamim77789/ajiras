<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PledgePayment extends Model
{
    protected $table = "pledge_payments";

    public function payment_method()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method_id');
    }
}
