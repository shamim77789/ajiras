<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $table = "follow_ups";

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function assigned_to()
    {
        return $this->hasOne(User::class, 'id', 'assigned_to_id');
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }

    public function category()
    {
        return $this->hasOne(FollowUpCategory::class, 'id', 'follow_up_category_id');
    }


}
