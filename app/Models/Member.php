<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    protected $table = "members";

    public function loans()
    {
        return $this->hasMany(Loan::class, 'borrower_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function pledges()
    {
        return $this->hasMany(Pledge::class, 'member_id', 'id');
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class, 'member_id', 'id');
    }

    public function tags()
    {
        return $this->hasMany(MemberTag::class, 'member_id', 'id');
    }

    public function attendance()
    {
        return $this->hasMany(EventAttendance::class, 'member_id', 'id');
    }
    public function families()
    {
        return $this->hasMany(FamilyMember::class, 'member_id', 'id');
    }
    public function family()
    {
        return $this->hasOne(Family::class, 'member_id', 'id');
    }
}
