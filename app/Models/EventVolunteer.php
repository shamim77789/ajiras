<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventVolunteer extends Model
{
    protected $table = "event_volunteers";

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }
}
