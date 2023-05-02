<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = "events";

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function calendar()
    {
        return $this->hasOne(EventCalendar::class, 'id', 'event_calendar_id');
    }

    public function location()
    {
        return $this->hasOne(EventLocation::class, 'id', 'event_location_id');
    }
    public function attenders()
    {
        return $this->hasMany(EventAttendance::class, 'event_id', 'id');
    }
    public function volunteers()
    {
        return $this->hasMany(EventVolunteer::class, 'event_id', 'id');
    }
    public function payments()
    {
        return $this->hasMany(EventPayment::class, 'event_id', 'id');
    }
}
