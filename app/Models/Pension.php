<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pension extends Model
{
    protected $table = "pension";

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}