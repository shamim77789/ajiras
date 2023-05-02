<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAttendance extends Model
{
    protected $table = "event_attendance";

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }
    public function event()
    {
        return $this->hasOne(Event::class, 'id', 'event_id');
    }

}
