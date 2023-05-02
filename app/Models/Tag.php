<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = "tags";
    public $timestamps = false;

    public function members()
    {
        return $this->hasMany(MemberTag::class, 'tag_id', 'id');
    }

}
