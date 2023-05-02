<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPayment extends Model
{
    protected $table = "event_payments";

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function payment_method()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method_id');
    }

    public function valuations()
    {
        return $this->hasMany(AssetValuation::class, 'asset_id', 'id')->orderBy('date', 'desc');
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }
}
