<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pledge extends Model
{
    protected $table = "pledges";

    public function campaign()
    {
        return $this->hasOne(Campaign::class, 'id', 'campaign_id');
    }


    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }
}
