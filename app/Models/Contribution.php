<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    protected $table = "contributions";

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function batch()
    {
        return $this->hasOne(ContributionBatch::class, 'id', 'contribution_batch_id');
    }

    public function payment_method()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method_id');
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }
}
