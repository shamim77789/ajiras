<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberTag extends Model
{
    protected $table = "member_tags";

    public $timestamps = false;

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }
    public function tag()
    {
        return $this->hasOne(Tag::class, 'id', 'tag_id');
    }
}
