<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $table = "families";

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }
}
