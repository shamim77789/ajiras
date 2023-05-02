<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    protected $table = "family_members";

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }
    public function family()
    {
        return $this->hasOne(Family::class, 'id', 'family_id');
    }
}
